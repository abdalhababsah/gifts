@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
<style>
    .dz-remove {
        display: flex;
        align-self: center;
        justify-content: center;
        border: red solid 1px;
        border-radius: 5px;
        color: red;
        padding: 2px;
        margin: 10px;
        background-color: #f0f0f0;
    }
    .dz-remove:hover {
        background-color: #e0e0e0;
    }
</style>
    <div class="relative min-h-screen group-data-[sidebar-size=sm]:min-h-sm">
        <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
            <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

                {{-- Page Header --}}
                <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                    <div class="grow">
                        <h5 class="text-16">Edit Product</h5>
                    </div>
                    <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                        <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                            <a href="{{ route('admin.products.index') }}" class="text-slate-400 dark:text-zink-200">Products</a>
                        </li>
                        <li class="text-slate-700 dark:text-zink-100">Edit</li>
                    </ul>
                </div>

                {{-- Alert Messages --}}
                <x-form-alerts />

                <div class="grid grid-cols-1 xl:grid-cols-12 gap-x-5">
                    {{-- Left Side Cover Image --}}
                    <div class="xl:col-span-4">
                        {{-- Cover Image Upload Card --}}
                        <div class="card mb-5">
                            <div class="card-body">
                                <h6 class="mb-4 text-15">Cover Image</h6>
                                <div class="text-center">
                                    <div class="relative mx-auto mb-4">
                                        <div id="cover-image-preview" class="size-32 mx-auto rounded-md bg-slate-100 dark:bg-zink-600 border border-dashed flex items-center justify-center overflow-hidden">
                                            @if($product->cover_image_path)
                                                <img id="cover-preview-image" src="{{ Storage::url($product->cover_image_path) }}" alt="Cover Preview" class="size-full object-cover rounded-md">
                                                <i data-lucide="image" class="size-8 text-slate-400 hidden" id="cover-image-placeholder"></i>
                                            @else
                                                <i data-lucide="image" class="size-8 text-slate-400" id="cover-image-placeholder"></i>
                                                <img id="cover-preview-image" src="" alt="Cover Preview" class="size-full object-cover rounded-md hidden">
                                            @endif
                                        </div>
                                        <button type="button" onclick="document.getElementById('cover_image').click()" class="absolute -top-2 -right-2 size-6 bg-custom-500 text-white rounded-full flex items-center justify-center">
                                            <i data-lucide="pencil" class="size-3"></i>
                                        </button>
                                    </div>
                                    <input type="file" id="cover_image" name="cover_image" accept="image/*" class="hidden" onchange="previewCoverImage(this)">
                                    <p class="text-sm text-slate-500">Upload cover image. Max size: 10MB.</p>
                                   
                                    @error('cover_image')
                                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Product Preview Card --}}
                        <div class="card sticky top-[calc(theme('spacing.header')_*_1.3)]">
                            <div class="card-body">
                                <h6 class="mb-4 text-15">Product Card Preview</h6>

                                <div class="px-5 py-8 rounded-md bg-sky-50 dark:bg-zink-600" id="preview-image-container">
                                    @if($product->cover_image_path)
                                        <img id="preview-cover-image" src="{{ Storage::url($product->cover_image_path) }}" alt="Cover Preview" class="block mx-auto h-44">
                                        <div id="preview-placeholder" class="flex items-center justify-center h-44 hidden">
                                            <i data-lucide="image" class="size-12 text-slate-400"></i>
                                        </div>
                                    @else
                                        <div id="preview-placeholder" class="flex items-center justify-center h-44">
                                            <i data-lucide="image" class="size-12 text-slate-400"></i>
                                        </div>
                                        <img id="preview-cover-image" src="" alt="Cover Preview" class="block mx-auto h-44 hidden">
                                    @endif
                                </div>

                                <div class="mt-3">
                                    <h5 class="mb-2">$<span id="preview-price">{{ $product->price }}</span></h5>
                                    <h6 class="mb-1 text-15" id="preview-name">{{ $product->name_en }}</h6>
                                    <p class="text-slate-500 dark:text-zink-200" id="preview-category">{{ $product->category->name_en ?? 'Category' }}</p>
                                    <p class="text-slate-500 dark:text-zink-200">Stock: <span id="preview-stock">{{ $product->stock }}</span></p>
                                    <p class="text-slate-500 dark:text-zink-200">SKU: <span id="preview-sku">{{ $product->sku }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="xl:col-span-8">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="mb-4 text-15">Edit Product</h6>

                                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2 xl:grid-cols-12">
                                        {{-- Product Name EN --}}
                                        <div class="xl:col-span-6">
                                            <label for="name_en" class="inline-block mb-2 text-base font-medium">Product Name (EN)</label>
                                            <input type="text" id="name_en" name="name_en" value="{{ old('name_en', $product->name_en) }}"
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                placeholder="Product name in English" required>
                                            <p class="mt-1 text-sm text-slate-400 dark:text-zink-200">Enter the product name in English.</p>
                                            @error('name_en')
                                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
 
                                        {{-- Product Name AR --}}
                                        <div class="xl:col-span-6">
                                            <label for="name_ar" class="inline-block mb-2 text-base font-medium">Product Name (AR)</label>
                                            <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar', $product->name_ar) }}"
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                placeholder="Product name in Arabic" dir="rtl" required>
                                            <p class="mt-1 text-sm text-slate-400 dark:text-zink-200">Enter the product name in Arabic.</p>
                                            @error('name_ar')
                                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
 
                                        {{-- Stock Quantity --}}
                                        <div class="xl:col-span-4">
                                            <label for="stock" class="inline-block mb-2 text-base font-medium">Quantity</label>
                                            <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0"
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                placeholder="Quantity" required>
                                            @error('stock')
                                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
 
                                        {{-- SKU --}}
                                        <div class="xl:col-span-4">
                                            <label for="sku" class="inline-block mb-2 text-base font-medium">SKU</label>
                                            <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}"
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                placeholder="TWT-LP-ALU-08">
                                            @error('sku')
                                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
 
                                        {{-- Brand --}}
                                        <div class="xl:col-span-4">
                                            <label for="brand_id" class="inline-block mb-2 text-base font-medium">Brand</label>
                                            <select id="brand_id" name="brand_id"
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                data-choices data-choices-search-false required>
                                                <option value="">Select Brand</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('brand_id')
                                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
 
                                        {{-- Category --}}
                                        <div class="xl:col-span-4">
                                            <label for="category_id" class="inline-block mb-2 text-base font-medium">Category</label>
                                            <select id="category_id" name="category_id"
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                data-choices data-choices-search-false required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
 
                                        {{-- Status --}}
                                        <div class="xl:col-span-4">
                                            <label for="is_active" class="inline-block mb-2 text-base font-medium">Status</label>
                                            <select id="is_active" name="is_active"
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                data-choices data-choices-search-false>
                                                <option value="1" {{ old('is_active', $product->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ old('is_active', $product->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('is_active')
                                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                               {{-- Price --}}
                                               <div class="xl:col-span-4">
                                                <label for="price" class="inline-block mb-2 text-base font-medium">Price</label>
                                                <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" min="0" step="0.01"
                                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                    placeholder="$0.00" required>
                                                @error('price')
                                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
 
                                        {{-- Product Images Upload --}}
                                        <div class="lg:col-span-2 xl:col-span-12">
                                            <label class="inline-block mb-2 text-base font-medium">Product Images</label>
                                            <div class="flex items-center justify-center bg-white border border-dashed rounded-md cursor-pointer dropzone border-slate-300 dark:bg-zink-700 dark:border-zink-500"
                                                id="product-images-dropzone">
                                                <div class="fallback">
                                                    <input name="images[]" type="file" multiple="multiple" accept="image/*">
                                                </div>
                                                <div class="w-full py-5 text-lg text-center dz-message needsclick">
                                                    <div class="mb-3">
                                                        <i data-lucide="upload-cloud" class="block mx-auto size-12 text-slate-500 fill-slate-200 dark:text-zink-200 dark:fill-zink-500"></i>
                                                    </div>
                                                    <h5 class="mb-0 font-normal text-slate-500 dark:text-zink-200 text-15">
                                                        Drag and drop your product images or <a href="#!">browse</a> your product images
                                                    </h5>
                                                </div>
                                            </div>
 
                                            <ul class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-5" id="dropzone-preview">
                                                <li class="hidden" id="dropzone-preview-list">
                                                    <div class="border rounded border-slate-200 dark:border-zink-500 overflow-hidden">
                                                        <div class="p-2 text-center">
                                                            <div>
                                                                <div class="rounded-md bg-slate-100 dark:bg-zink-600 overflow-hidden" style="height: 120px;">
                                                                    <img data-dz-thumbnail class="w-full h-full object-cover" alt="Dropzone-Image">
                                                                </div>
                                                            </div>
                                                            <div class="pt-3">
                                                             
                                                                <strong class="error text-danger text-xs" data-dz-errormessage></strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
 
                                        {{-- Description EN --}}
                                        <div class="lg:col-span-2 xl:col-span-6">
                                            <div>
                                                <label for="description_en" class="inline-block mb-2 text-base font-medium">Description (EN)</label>
                                                <textarea id="description_en" name="description_en" rows="5"
                                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                    placeholder="Enter Description in English">{{ old('description_en', $product->description_en) }}</textarea>
                                                @error('description_en')
                                                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
 
                                        {{-- Description AR --}}
                                        <div class="lg:col-span-2 xl:col-span-6">
                                            <div>
                                                <label for="description_ar" class="inline-block mb-2 text-base font-medium">Description (AR)</label>
                                                <textarea id="description_ar" name="description_ar" rows="5"
                                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                    placeholder="Enter Description in Arabic" dir="rtl">{{ old('description_ar', $product->description_ar) }}</textarea>
                                                @error('description_ar')
                                                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
 
                                 
                                    </div>
 
                                    <div class="flex justify-end gap-2 mt-4">
                                        <a href="{{ route('admin.products.index') }}" class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-700 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">Cancel</a>
                                        <button type="submit" id="submitBtn" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Update Product</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
 
                                        {{-- Right Side - Intentionally Empty --}}
                </div>
            </div>
        </div>
    </div>
 @endsection
 
 @push('scripts')
   <script src="{{ asset('admin/assets/libs/dropzone/dropzone-min.js') }}"></script>
   <script>
        // Common alert functions are now in common.js
   </script>
    <script>
        // Initialize Dropzone
        Dropzone.autoDiscover = false;
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log("DOM fully loaded - initializing scripts");
            
            // Double check that the form exists
            const productForm = document.getElementById('productForm');
            if (!productForm) {
                console.error("Product form not found!");
            } else {
                console.log("Product form found:", productForm.id);
            }
            
            // Preview cover image function
            window.previewCoverImage = function(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        // Update the preview in the left sidebar
                        const previewImage = document.getElementById('cover-preview-image');
                        const placeholder = document.getElementById('cover-image-placeholder');
                        
                        previewImage.src = e.target.result;
                        previewImage.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                        
                        // Update the preview in the product card
                        const cardPreviewImage = document.getElementById('preview-cover-image');
                        const cardPlaceholder = document.getElementById('preview-placeholder');
                        
                        cardPreviewImage.src = e.target.result;
                        cardPreviewImage.classList.remove('hidden');
                        cardPlaceholder.classList.add('hidden');
                    }
                    
                    reader.readAsDataURL(input.files[0]);
                }
            }
            
            // Update preview card when form fields change
            document.getElementById('name_en').addEventListener('input', function() {
                document.getElementById('preview-name').textContent = this.value || 'Product Name';
            });
            
            document.getElementById('price').addEventListener('input', function() {
                document.getElementById('preview-price').textContent = this.value || '0.00';
            });
            
            document.getElementById('stock').addEventListener('input', function() {
                document.getElementById('preview-stock').textContent = this.value || '0';
            });
            
            document.getElementById('sku').addEventListener('input', function() {
                document.getElementById('preview-sku').textContent = this.value || 'Auto-generated';
            });
            
            document.getElementById('category_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                document.getElementById('preview-category').textContent = selectedOption.text !== 'Select Category' ? selectedOption.text : 'Category';
            });
            // Initialize Dropzone without auto-processing
            const myDropzone = new Dropzone("#product-images-dropzone", {
                url: "{{ route('admin.products.update', $product) }}",
                autoProcessQueue: false,
                uploadMultiple: true,
                paramName: "images",
                acceptedFiles: "image/*",
                maxFilesize: 10, // MB
                addRemoveLinks: true,
                previewsContainer: "#dropzone-preview",
                previewTemplate: document.querySelector('#dropzone-preview-list').innerHTML,
                // Prevent Dropzone from hijacking the form
                autoDiscover: false,
                clickable: true,
                init: function() {
                    let myDropzone = this;
                    
                    // Set up event handlers for file removal
                    this.on("removedfile", function(file) {
                        if (file._imageId) {
                            // This is an existing file, add it to the delete list
                            console.log("Marking image for deletion:", file._imageId);
                            const deleteInput = document.createElement('input');
                            deleteInput.type = 'hidden';
                            deleteInput.name = 'delete_images[]';
                            deleteInput.value = file._imageId;
                            document.getElementById('productForm').appendChild(deleteInput);
                        }
                    });
                    
                    // Display existing product images
                    @if($product->images && $product->images->count() > 0)
                        @foreach($product->images as $image)
                            // Create a mock file representing the existing image
                            let mockFile{{ $image->id }} = { 
                                name: "{{ basename($image->image_path) }}", 
                                size: 12345, 
                                accepted: true,
                                kind: 'image',
                                upload: { uuid: "{{ $image->id }}" },
                                dataURL: "{{ Storage::url($image->image_path) }}",
                                status: Dropzone.ADDED,
                                previewElement: null,
                                imageId: "{{ $image->id }}"
                            };
                            
                            // Call the default addedfile event handler
                            myDropzone.emit("addedfile", mockFile{{ $image->id }});
                            
                            // And optionally show the thumbnail of the file
                            myDropzone.emit("thumbnail", mockFile{{ $image->id }}, "{{ Storage::url($image->image_path) }}");
                            
                            // Make sure that there is no progress bar, etc...
                            myDropzone.emit("complete", mockFile{{ $image->id }});
                            
                            // Add to files array so it's included in the submitted data
                            myDropzone.files.push(mockFile{{ $image->id }});
                            
                            // Store the image ID for later use
                            mockFile{{ $image->id }}._imageId = "{{ $image->id }}";
                        @endforeach
                    @endif
                }
            });
            
            // Handle form submission
            document.getElementById('productForm').addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission
                
                // Get all files from Dropzone
                const files = myDropzone.getAcceptedFiles();
                console.log("Files in dropzone:", files.length);
                
                // Create FormData from the form
                const formData = new FormData(this);
                
                // Add method override for PUT
                formData.append('_method', 'PUT');
                
                // Handle cover image from the hidden input
                const coverImageInput = document.getElementById('cover_image');
                if (coverImageInput && coverImageInput.files && coverImageInput.files[0]) {
                    formData.append('cover_image', coverImageInput.files[0]);
                    console.log("Adding cover image:", coverImageInput.files[0].name);
                }
                
                // Add all Dropzone files to the FormData
                if (files.length > 0) {
                    files.forEach(function(file, index) {
                        // Only add new files (not mock files) to the FormData
                        if (!file._imageId && file instanceof File) {
                            formData.append('images[]', file);
                            console.log("Adding new file to FormData:", file.name);
                        } else {
                            // For existing files, just pass the ID to keep them
                            console.log("Keeping existing file:", file.name);
                        }
                    });
                }
                
                // Log FormData contents (for debugging)
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + (pair[1] instanceof File ? pair[1].name : pair[1]));
                }
                
                // Submit the form with AJAX
                fetch(this.action, {
                    method: 'POST', // Always POST for FormData with files
                    body: formData,
                    // Don't set Content-Type header - browser will set it with boundary for multipart/form-data
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'  // This marks the request as AJAX
                    }
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = "{{ route('admin.products.index') }}";
                    } else {
                        // Check content type to handle both JSON and HTML responses
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Something went wrong');
                            });
                        } else {
                            return response.text().then(text => {
                                console.error('Server returned HTML error:', text);
                                throw new Error('Server error occurred. Check console for details.');
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorAlert(error.message || 'An error occurred while submitting the form');
                });
            });
        });
        
        // Cover image preview
        function previewCoverImage(input) {
            if (input.files && input.files[0]) {
                if (input.files[0].size > 10485760) {
                    showValidationAlert('File size must be less than 10MB');
                    input.value = '';
                    return;
                }
 
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
                if (!allowedTypes.includes(input.files[0].type)) {
                    showValidationAlert('Please select a valid image file (JPEG, PNG, JPG, GIF, SVG)');
                    input.value = '';
                    return;
                }
 
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('cover-image-placeholder').classList.add('hidden');
                    document.getElementById('cover-preview-image').classList.remove('hidden');
                    document.getElementById('cover-preview-image').src = e.target.result;
                    
                    // Update main preview
                    document.getElementById('preview-placeholder').classList.add('hidden');
                    document.getElementById('preview-cover-image').classList.remove('hidden');
                    document.getElementById('preview-cover-image').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
 
        // Live preview updates
        document.addEventListener('DOMContentLoaded', function() {
            // Product name preview
            document.getElementById('name_en').addEventListener('input', function() {
                document.getElementById('preview-name').textContent = this.value || 'Product Name';
            });
 
            // Price preview
            document.getElementById('price').addEventListener('input', function() {
                document.getElementById('preview-price').textContent = this.value || '0.00';
            });
 
            // Stock preview
            document.getElementById('stock').addEventListener('input', function() {
                document.getElementById('preview-stock').textContent = this.value || '0';
            });
 
            // SKU preview
            document.getElementById('sku').addEventListener('input', function() {
                document.getElementById('preview-sku').textContent = this.value || 'Auto-generated';
            });
 
            // Category preview
            document.getElementById('category_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                document.getElementById('preview-category').textContent = selectedOption.text !== 'Select Category' ? selectedOption.text : 'Category';
            });
        });
    </script>
 @endpush

 