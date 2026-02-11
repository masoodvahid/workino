<?php

namespace App\Http\Controllers;

use App\Enums\City;
use App\Enums\UserEducation;
use App\Models\User;
use App\Support\NumberNormalizer;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Throwable;

class UserController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        abort_unless($userId, 403);
        $user = User::query()->with('userMetas')->findOrFail($userId);
        $meta = $user->userMetas->pluck('value', 'key')->all();

        $typeLabel = match ($user?->type) {
            'man' => 'آقا',
            'woman' => 'خانم',
            'company' => 'حقوقی',
            default => '-',
        };

        $educationLabel = UserEducation::tryFrom($meta['education'] ?? null)?->getLabel();
        $cityLabel = City::tryFrom($meta['city'] ?? null)?->getLabel();

        $birthDayLabel = $this->toJalaliDate($meta['birth_day'] ?? null);

        $profile = [
            'initial' => $user?->name ? mb_substr($user->name, 0, 1) : '؟',
            'name' => $user?->name ?? '-',
            'mobile' => $user?->mobile ?? '-',
            'email' => $user?->email ?? '-',
            'type' => $typeLabel,
            'status' => $user?->status?->getLabel() ?? '-',
            'note' => $user?->note ?? '-',
            'national_id_label' => $user?->type === 'company' ? 'شناسه ملی' : 'کد ملی',
            'national_id' => $meta['national_id'] ?? '-',
            'reg_number' => $meta['reg_number'] ?? '-',
            'birth_day' => $birthDayLabel ?? '-',
            'education' => $educationLabel ?? $meta['education'] ?? '-',
            'major' => $meta['major'] ?? '-',
            'university' => $meta['university'] ?? '-',
            'city' => $cityLabel ?? $meta['city'] ?? '-',
            'address' => $meta['address'] ?? '-',
            'postal_code' => $meta['postal_code'] ?? '-',
        ];

        $form = [
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'note' => $user->note,
            'national_id' => $meta['national_id'] ?? null,
            'reg_number' => $meta['reg_number'] ?? null,
            'birth_day' => $this->toJalaliDate($meta['birth_day'] ?? null),
            'education' => $meta['education'] ?? null,
            'major' => $meta['major'] ?? null,
            'university' => $meta['university'] ?? null,
            'city' => $meta['city'] ?? null,
            'address' => $meta['address'] ?? null,
            'postal_code' => $meta['postal_code'] ?? null,
        ];

        $educationOptions = UserEducation::cases();
        $cityOptions = City::cases();

        return view('profile.index', compact('user', 'profile', 'form', 'educationOptions', 'cityOptions'));
    }

    public function update(Request $request)
    {
        $userId = Auth::id();
        abort_unless($userId, 403);
        $user = User::query()->findOrFail($userId);

        $cityValues = array_map(fn (City $city): string => $city->value, City::cases());
        $educationValues = array_map(fn (UserEducation $education): string => $education->value, UserEducation::cases());

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'note' => ['nullable', 'string'],
            'national_id' => ['nullable'],
            'reg_number' => ['nullable'],
            'birth_day' => ['nullable', 'regex:/^\d{4}\/\d{2}\/\d{2}$/'],
            'education' => ['nullable', Rule::in($educationValues)],
            'major' => ['nullable', 'string', 'max:512'],
            'university' => ['nullable', 'string', 'max:512'],
            'city' => ['nullable', Rule::in($cityValues)],
            'address' => ['nullable', 'string', 'max:1024'],
            'postal_code' => ['nullable', 'digits:10'],
        ];

        if ($user->type === 'company') {
            $rules['national_id'] = ['nullable', 'digits_between:10,12'];
            $rules['reg_number'] = ['nullable', 'digits_between:1,10'];
        }

        if (in_array($user->type, ['man', 'woman'], true)) {
            $rules['national_id'] = ['nullable', 'digits:10'];
        }

        $payload = $request->validate($rules);

        $payload['national_id'] = NumberNormalizer::normalize($payload['national_id'] ?? null);
        $payload['reg_number'] = NumberNormalizer::normalize($payload['reg_number'] ?? null);
        $payload['postal_code'] = NumberNormalizer::normalize($payload['postal_code'] ?? null);
        $payload['birth_day'] = $this->toGregorianDate($payload['birth_day'] ?? null);

        $user->update([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'note' => $payload['note'] ?? null,
        ]);

        $metaValues = [
            'national_id' => $payload['national_id'] ?? null,
            'reg_number' => $payload['reg_number'] ?? null,
            'birth_day' => $payload['birth_day'] ?? null,
            'education' => $payload['education'] ?? null,
            'major' => $payload['major'] ?? null,
            'university' => $payload['university'] ?? null,
            'city' => $payload['city'] ?? null,
            'address' => $payload['address'] ?? null,
            'postal_code' => $payload['postal_code'] ?? null,
        ];

        if ($user->type === 'company') {
            $metaValues['birth_day'] = null;
            $metaValues['education'] = null;
            $metaValues['major'] = null;
            $metaValues['university'] = null;
        }

        if (in_array($user->type, ['man', 'woman'], true)) {
            $metaValues['reg_number'] = null;
        }

        $user->setMetaValues($metaValues);

        return redirect()
            ->route('profile.index')
            ->with('status', 'اطلاعات شما با موفقیت بروزرسانی شد.');
    }

    private function toJalaliDate(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        try {
            return verta($value)->format('Y/m/d');
        } catch (Throwable) {
            return $value;
        }
    }

    private function toGregorianDate(?string $value): ?string
    {
        $value = NumberNormalizer::normalize($value);

        if (blank($value)) {
            return null;
        }

        try {
            return Verta::parse($value)->toCarbon()->format('Y-m-d');
        } catch (Throwable) {
            return null;
        }
    }
}
