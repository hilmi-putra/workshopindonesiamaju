<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class ManuscriptSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'author_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'genre' => ['nullable', 'string', 'max:100'],
            'synopsis' => ['required', 'string', 'min:20', 'max:5000'],
            'manuscript_file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul naskah wajib diisi.',
            'author_name.required' => 'Nama penulis wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'synopsis.required' => 'Sinopsis singkat wajib diisi.',
            'synopsis.min' => 'Sinopsis minimal 20 karakter.',
            'manuscript_file.required' => 'File naskah (PDF/DOC/DOCX) wajib diunggah.',
            'manuscript_file.mimes' => 'Format file naskah harus PDF, DOC, atau DOCX.',
            'manuscript_file.max' => 'Ukuran file naskah maksimal 10 MB.',
        ];
    }
}
