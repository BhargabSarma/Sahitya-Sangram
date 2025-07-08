<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Publish Your Book With Us</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/header-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('css/void-cards.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('css/shelf-books.css') }}">
    <style>
        .form {
            background-color: #fff;
            box-shadow: 0 10px 60px rgb(218, 229, 255);
            border: 1px solid rgb(159, 159, 160);
            border-radius: 20px;
            padding: 2rem .7rem .7rem .7rem;
            text-align: center;
            font-size: 1.125rem;
            max-width: 320px;
            margin: 0 auto;
        }
        .form-title {
            color: #000000;
            font-size: 1.8rem;
            font-weight: 500;
        }
        .form-paragraph {
            margin-top: 10px;
            font-size: 0.9375rem;
            color: rgb(105, 105, 105);
        }
        .drop-container {
            background-color: #fff;
            position: relative;
            display: flex;
            gap: 10px;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 10px;
            margin-top: 2.1875rem;
            border-radius: 10px;
            border: 2px dashed rgb(171, 202, 255);
            color: #444;
            cursor: pointer;
            transition: background .2s ease-in-out, border .2s ease-in-out;
        }
        .drop-container:hover {
            background: rgba(0, 140, 255, 0.164);
            border-color: rgba(17, 17, 17, 0.616);
        }
        .drop-container:hover .drop-title {
            color: #222;
        }
        .drop-title {
            color: #444;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            transition: color .2s ease-in-out;
        }
        #cover-input, #pdf-input {
            width: 350px;
            max-width: 100%;
            color: #444;
            padding: 2px;
            background: #fff;
            border-radius: 10px;
            border: 1px solid rgba(8, 8, 8, 0.288);
        }
        #cover-input::file-selector-button, #pdf-input::file-selector-button {
            margin-right: 20px;
            border: none;
            background: #084cdf;
            padding: 10px 20px;
            border-radius: 10px;
            color: #fff;
            cursor: pointer;
            transition: background .2s ease-in-out;
        }
        #cover-input::file-selector-button:hover, #pdf-input::file-selector-button:hover {
            background: #0d45a5;
        }
    </style>
</head>
<body>
    @include('components.header')
    <div class="container mx-auto py-32 flex justify-center">
        <form action="#" method="POST" enctype="multipart/form-data" class="w-full max-w-xl bg-white p-8 rounded-xl shadow-lg space-y-8">
            @csrf

            <!-- Step Progress Bar -->
            <div class="flex flex-col items-center mb-8">
                <div class="flex items-center w-full max-w-xs mb-2">
                    <div class="flex-1 flex items-center">
                        <div id="step-indicator-1" class="w-8 h-8 flex items-center justify-center rounded-full bg-indigo-600 text-white font-bold border-4 border-indigo-600 z-10 transition-all duration-300">1</div>
                        <div class="flex-1 h-1 bg-gray-300 mx-1 relative">
                            <div id="progress-bar" class="absolute left-0 top-0 h-1 bg-indigo-600 rounded transition-all duration-300" style="width: 50%"></div>
                        </div>
                        <div id="step-indicator-2" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 text-gray-700 font-bold border-4 border-gray-200 z-10 transition-all duration-300">2</div>
                    </div>
                </div>
                <div class="flex justify-between w-full max-w-xs text-xs text-gray-600">
                    <span class="ml-1 font-semibold" id="label-step-1">Book Details</span>
                    <span class="mr-1 font-semibold" id="label-step-2">Author Details</span>
                </div>
            </div>

            <!-- Step 1: Book Details -->
            <div id="step-1">
                <h2 class="text-2xl font-bold mb-4 text-indigo-700">Book Details</h2>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Title</label>
                    <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Subtitle</label>
                    <input type="text" name="subtitle" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Description</label>
                    <textarea name="description" class="w-full border rounded px-3 py-2" rows="3" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Category</label>
                    <select name="category" id="category-select" class="w-full border rounded px-3 py-2">
                        <option value="" disabled selected>Select a category</option>
                        <option value="Fiction">Fiction</option>
                        <option value="Non-fiction">Non-fiction</option>
                        <option value="Biography">Biography</option>
                        <option value="Children">Children</option>
                        <option value="Education">Education</option>
                        <option value="Comics">Comics</option>
                        <option value="Others">Others</option>
                    </select>
                    <input type="text" name="category_other" id="category-other" class="w-full border rounded px-3 py-2 mt-2 hidden" placeholder="Please specify category">
                </div>
                <!-- Custom styled PDF upload -->
                <div class="mb-4">
                    <div class="form">
                        <span class="form-title">Upload your Book PDF</span>
                        <p class="form-paragraph">File should be a PDF</p>
                        <label for="pdf-input" class="drop-container">
                            <span class="drop-title">Drop files here</span>
                            or
                            <input type="file" name="pdf" accept="application/pdf" id="pdf-input" required>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" id="next-btn" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">Next</button>
                </div>
            </div>

            <!-- Step 2: Author Details (hidden by default) -->
            <div id="step-2" class="hidden">
                <h2 class="text-2xl font-bold mb-4 text-indigo-700">Author Details</h2>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Author Name</label>
                    <input type="text" name="author" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Email</label>
                    <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Phone Number</label>
                    <input type="tel" name="phone" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Short Bio</label>
                    <textarea name="bio" class="w-full border rounded px-3 py-2" rows="3"></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="button" id="back-btn" class="bg-gray-300 text-gray-800 px-6 py-2 rounded hover:bg-gray-400">Back</button>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- GSAP & ScrollTrigger for animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/CustomEase.min.js"></script>

    <!-- SplitType for animated text lines -->
    <script src="https://unpkg.com/split-type"></script>

    <!-- Your custom JS (should be after all libraries) -->
    <!-- If you have a separate hero parallax JS, include it here -->
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/hero-gsap.js') }}"></script>
    
    
    @include('components.footer')
    <script>
        // Stepper logic and progress bar
        const step1 = document.getElementById('step-1');
        const step2 = document.getElementById('step-2');
        const nextBtn = document.getElementById('next-btn');
        const backBtn = document.getElementById('back-btn');
        const progressBar = document.getElementById('progress-bar');
        const stepIndicator1 = document.getElementById('step-indicator-1');
        const stepIndicator2 = document.getElementById('step-indicator-2');

        nextBtn.addEventListener('click', function() {
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            progressBar.style.width = '100%';
            stepIndicator1.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
            stepIndicator1.classList.add('bg-gray-200', 'text-gray-700', 'border-gray-200');
            stepIndicator2.classList.remove('bg-gray-200', 'text-gray-700', 'border-gray-200');
            stepIndicator2.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');
        });

        backBtn.addEventListener('click', function() {
            step2.classList.add('hidden');
            step1.classList.remove('hidden');
            progressBar.style.width = '50%';
            stepIndicator2.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
            stepIndicator2.classList.add('bg-gray-200', 'text-gray-700', 'border-gray-200');
            stepIndicator1.classList.remove('bg-gray-200', 'text-gray-700', 'border-gray-200');
            stepIndicator1.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');
        });

        document.getElementById('category-select').addEventListener('change', function() {
            const otherInput = document.getElementById('category-other');
            if (this.value === 'Others') {
                otherInput.classList.remove('hidden');
                otherInput.required = true;
            } else {
                otherInput.classList.add('hidden');
                otherInput.required = false;
            }
        });
    </script>
</body>
</html>