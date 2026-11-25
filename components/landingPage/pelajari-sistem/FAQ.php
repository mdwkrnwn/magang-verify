<section class="py-20 bg-slate-50" id="faq">
    <div class="max-w-4xl mx-auto px-6 lg:px-8">

        <div class="text-center mb-12">
            <span class="text-blue-600 font-semibold text-sm tracking-wider uppercase">
                Frequently Asked Questions
            </span>

            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-3">
                Pertanyaan yang Sering Diajukan
            </h2>

            <p class="mt-4 text-slate-600">
                Temukan jawaban atas beberapa pertanyaan mengenai VerifyMagang.
            </p>
        </div>

        <?php
        $faqs = [
            [
                'question' => 'Apa itu VerifyMagang?',
                'answer' => 'VerifyMagang merupakan platform digital yang dirancang untuk membantu pengelolaan kegiatan magang dengan melibatkan berbagai pihak dalam satu sistem.'
            ],
            [
                'question' => 'Siapa saja yang dapat menggunakan VerifyMagang?',
                'answer' => 'VerifyMagang digunakan oleh pihak-pihak yang terlibat dalam kegiatan magang, seperti mahasiswa, dosen, tenaga kependidikan, koordinator magang, dan mitra.'
            ],
            [
                'question' => 'Apakah setiap pengguna memiliki fitur yang sama?',
                'answer' => 'Tidak. Setiap pengguna memiliki akses dan fitur yang disesuaikan dengan peran serta tanggung jawabnya dalam sistem.'
            ],
            [
                'question' => 'Bagaimana cara menggunakan VerifyMagang?',
                'answer' => 'Pengguna dapat masuk ke sistem menggunakan akun yang telah disediakan, kemudian mengakses dashboard dan fitur sesuai dengan peran masing-masing.'
            ],
            [
                'question' => 'Apakah kegiatan magang dapat dipantau melalui sistem?',
                'answer' => 'VerifyMagang menyediakan fitur yang mendukung pengelolaan dan pemantauan kegiatan magang sehingga proses dapat dikelola secara lebih terstruktur.'
            ],
            [
                'question' => 'Bagaimana jika mengalami kendala saat menggunakan sistem?',
                'answer' => 'Jika mengalami kendala, pengguna dapat menghubungi pihak pengelola atau administrator sistem untuk mendapatkan bantuan lebih lanjut.'
            ]
        ];
        ?>

        <div class="space-y-4">

            <?php foreach ($faqs as $faq): ?>

                <details class="group bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-blue-300 transition">

                    <summary class="flex items-center justify-between gap-4 p-5 cursor-pointer list-none">
                        <span class="font-semibold text-slate-800">
                            <?= htmlspecialchars($faq['question']) ?>
                        </span>

                        <span class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 group-open:bg-blue-100 group-open:text-blue-600 transition flex-shrink-0">
                            <svg
                                class="w-4 h-4 transition-transform group-open:rotate-45"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M12 5v14" />
                                <path d="M5 12h14" />
                            </svg>
                        </span>
                    </summary>

                    <div class="px-5 pb-5 text-sm leading-relaxed text-slate-600">
                        <?= htmlspecialchars($faq['answer']) ?>
                    </div>

                </details>

            <?php endforeach; ?>

        </div>

    </div>
</section>