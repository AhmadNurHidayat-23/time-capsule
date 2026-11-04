<?php

namespace App\Http\Requests;

use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreCapsuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['title' => 'required|string|max:120', 'body' => 'required|string|max:20000', 'opens_at' => ['required', 'date_format:Y-m-d\TH:i']];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $validator->errors()->has('opens_at')) {
                $time = CarbonImmutable::createFromFormat('!Y-m-d\TH:i', $this->input('opens_at'), 'Asia/Jakarta');
                if ($time->lessThanOrEqualTo(CarbonImmutable::now('UTC'))) {
                    $validator->errors()->add('opens_at', 'Waktu pembukaan harus berada di masa depan (Asia/Jakarta).');
                }
            }
        });
    }

    public function messages(): array
    {
        return ['required' => ':attribute wajib diisi.', 'string' => ':attribute harus berupa teks.', 'max' => ':attribute maksimal :max karakter.', 'date_format' => 'Masukkan tanggal dan waktu pembukaan yang valid.'];
    }

    public function attributes(): array
    {
        return ['title' => 'Judul pesan', 'body' => 'Isi pesan', 'opens_at' => 'Waktu pembukaan'];
    }
}
