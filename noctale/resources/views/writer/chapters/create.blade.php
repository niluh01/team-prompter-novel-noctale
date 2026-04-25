<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tulis Bab Baru: {{ $novel->title }}</h2>
    </x-slot>
    <div class="py-12" x-data="{ showPreview: false, previewContent: '' }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 p-8">
                <form id="chapterForm" action="{{ route('writer.novels.chapters.store', $novel) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-8 flex flex-col md:flex-row gap-6 items-start">
                        <div class="w-full md:w-1/2">
                            <label class="block text-gray-700 font-bold mb-3 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Gambar Header Bab (Opsional)
                            </label>
                            <div class="relative group">
                                <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer border border-dashed border-gray-300 rounded-xl p-2 bg-gray-50/50">
                            </div>
                        </div>
                        <div class="w-full md:w-1/2">
                            <label class="block text-gray-700 font-bold mb-3 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Impor dari File .txt
                            </label>
                            <input type="file" id="manuscript_upload" accept=".txt" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-all cursor-pointer border border-dashed border-gray-300 rounded-xl p-2 bg-gray-50/50" onchange="readManuscript(this)">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2 text-sm">Urutan Bab Ke-</label>
                            <input type="number" name="chapter_number" value="{{ $nextNumber }}" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all font-semibold" required>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-gray-700 font-bold mb-2 text-sm">Status Penerbitan</label>
                            <select name="publish_status" id="publish_status" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all font-semibold" required onchange="toggleScheduledAt()">
                                <option value="published">Publikasikan Sekarang</option>
                                <option value="draft">Simpan sebagai Draft</option>
                                <option value="scheduled">Jadwalkan Otomatis</option>
                            </select>
                        </div>
                        <div id="scheduled_at_container" class="hidden">
                            <label class="block text-gray-700 font-bold mb-2 text-sm">Waktu Penayangan</label>
                            <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all font-semibold">
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-gray-700 font-bold mb-3 text-sm flex items-center justify-between">
                            <span>Judul Bab</span>
                            <span class="text-xs font-normal text-gray-400">Gunakan judul yang menarik pembaca</span>
                        </label>
                        <input type="text" name="title" placeholder="Contoh: Bab 1 - Awal Mula Petualangan" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all text-lg font-bold py-3 px-4" required>
                    </div>

                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-3">
                            <label class="text-gray-700 font-bold text-sm flex items-center">
                                <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Isi Bab Novel
                            </label>
                            <button type="button" @click="previewContent = setPreview(); showPreview = true" class="text-xs bg-purple-50 text-purple-700 font-bold py-1.5 px-4 rounded-full hover:bg-purple-100 transition-all flex items-center border border-purple-100">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Pratinjau (Preview)
                            </button>
                        </div>
                        <div class="rounded-2xl border border-gray-200 overflow-hidden shadow-inner bg-gray-50">
                            <!-- Hidden input to store Quill content -->
                            <input type="hidden" name="content" id="content_area" value="{{ old('content') }}">
                            <!-- Quill editor container -->
                            <div id="editor-container" class="bg-white" style="border:none;"></div>
                        </div>
                    </div>

                    <div class="mt-10 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm border-t border-gray-100 pt-8">
                        <a href="{{ route('writer.novels.chapters.index', $novel) }}" class="text-gray-500 hover:text-gray-800 font-semibold px-6 py-2.5 transition-all">
                            Kembali ke Daftar
                        </a>
                        <div class="flex gap-4 w-full sm:w-auto">
                            <button type="submit" class="flex-1 sm:flex-none px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                                Publikasikan Cerita
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Modal -->
        <div x-show="showPreview" class="fixed inset-0 z-50 overflow-y-auto" style="display: none; z-index: 9999;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div @click="showPreview = false" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm"></div>
                
                <div class="inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl">
                    <div class="flex items-center justify-between px-8 py-4 border-b border-gray-100 bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest">Tampilan untuk Pembaca</h3>
                        </div>
                        <button @click="showPreview = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="px-8 py-10 md:px-12 md:py-14 max-h-[75vh] overflow-y-auto bg-white">
                        <div class="prose prose-lg md:prose-xl max-w-3xl mx-auto prose-slate prose-headings:font-bold prose-p:leading-relaxed prose-img:rounded-2xl" x-html="previewContent">
                        </div>
                    </div>

                    <div class="px-8 py-6 bg-gray-50 flex justify-center">
                        <button @click="showPreview = false" class="px-10 py-3 bg-gray-800 text-white font-bold rounded-xl hover:bg-gray-900 transition-all shadow-xl">
                            Tutup Pratinjau
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quill Rich Text Editor (100% FREE) -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Register custom fonts
            var Font = Quill.import('formats/font');
            Font.whitelist = ['serif', 'sans-serif', 'monospace', 'playfair', 'georgia', 'roboto'];
            Quill.register(Font, true);

            // Configure Toolbar
            var toolbarOptions = [
                [{ 'header': [1, 2, 3, false] }],
                [{ 'font': Font.whitelist }],
                [{ 'size': ['small', false, 'large', 'huge'] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ];

            var quill = new Quill('#editor-container', {
                modules: {
                    toolbar: toolbarOptions
                },
                placeholder: 'Mulailah merangkai kata-kata ajaib Anda...',
                theme: 'snow'
            });

            // Set initial content if editing or from manuscript
            var hiddenInput = document.getElementById('content_area');
            if (hiddenInput.value) {
                quill.root.innerHTML = hiddenInput.value;
            }

            // Sync with hidden input on change
            quill.on('text-change', function() {
                hiddenInput.value = quill.root.innerHTML;
            });

            // Custom Image Handler
            var toolbar = quill.getModule('toolbar');
            toolbar.addHandler('image', function() {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.click();

                input.onchange = function() {
                    var file = input.files[0];
                    var formData = new FormData();
                    formData.append('file', file);

                    // Show loading state (optional)
                    
                    fetch('{{ route("writer.chapters.upload_image") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.url) {
                            var range = quill.getSelection();
                            quill.insertEmbed(range.index, 'image', result.url);
                        } else {
                            alert('Gagal mengunggah gambar');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengunggah');
                    });
                };
            });

            window.quillEditor = quill;
        });

        function toggleScheduledAt() {
            const status = document.getElementById('publish_status').value;
            const container = document.getElementById('scheduled_at_container');
            const input = document.getElementById('scheduled_at');
            if (status === 'scheduled') {
                container.classList.remove('hidden');
                container.classList.add('block');
                input.required = true;
            } else {
                container.classList.add('hidden');
                container.classList.remove('block');
                input.required = false;
            }
        }

        function readManuscript(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    var content = e.target.result.replace(/\n/g, '<p>').replace(/<\/p>/g, '') + '</p>';
                    if (window.quillEditor) {
                        window.quillEditor.root.innerHTML = content;
                        document.getElementById('content_area').value = content;
                    } else {
                        document.getElementById('content_area').value = content;
                    }
                };
                reader.readAsText(input.files[0]);
            }
        }

        function setPreview() {
            if (window.quillEditor) {
                return window.quillEditor.root.innerHTML;
            }
            return '';
        }
    </script>
    <style>
        .ql-editor {
            min-height: 500px;
            font-size: 18px;
            line-height: 1.8;
            font-family: 'Georgia', serif;
        }
        .ql-container.ql-snow {
            border: none;
        }
        .ql-toolbar.ql-snow {
            border: none;
            border-bottom: 1px solid #f3f4f6;
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        /* Custom Font Support in Toolbar */
        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="georgia"]::before,
        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="georgia"]::before { content: 'Georgia'; font-family: 'Georgia', serif; }
        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="playfair"]::before,
        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="playfair"]::before { content: 'Playfair'; font-family: 'Playfair Display', serif; }
        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="roboto"]::before,
        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="roboto"]::before { content: 'Roboto'; font-family: 'Roboto', sans-serif; }
    </style>
</x-app-layout>