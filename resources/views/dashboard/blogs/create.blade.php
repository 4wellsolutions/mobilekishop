@extends('layouts.dashboard')
@section('title', 'Create Blog Post')

@section('content')
    <div class="admin-page-header">
        <div>
            <h1>Create Blog Post</h1>
            <div class="breadcrumb-nav">
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
                <span class="separator">/</span>
                <a href="{{ route('dashboard.blogs.index') }}">Blog Posts</a>
                <span class="separator">/</span>
                Create
            </div>
        </div>
    </div>

    @include('includes.info-bar')

    <form action="{{ route('dashboard.blogs.store') }}" method="POST" enctype="multipart/form-data" id="blogForm">
        @csrf

        <div class="row">
            <div class="col-md-8">
                {{-- Content Card --}}
                <div class="admin-card" style="margin-bottom:24px;">
                    <div class="admin-card-header">
                        <h2><i class="fas fa-pen" style="margin-right:8px; opacity:0.5;"></i>Content</h2>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-form-group">
                            <label class="admin-form-label">Title <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="title" class="admin-form-control" id="blogTitle"
                                value="{{ old('title') }}" required>
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-form-label">Slug</label>
                            <input type="text" name="slug" class="admin-form-control" id="blogSlug"
                                value="{{ old('slug') }}" placeholder="Auto-generated from title if empty">
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-form-label">Excerpt</label>
                            <textarea name="excerpt" class="admin-form-control" rows="3"
                                id="blogExcerpt">{{ old('excerpt') }}</textarea>
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-form-label">Body</label>
                            @include('includes.html-editor', ['name' => 'body', 'value' => old('body', ''), 'id' => 'blogBody'])
                        </div>
                    </div>
                </div>

                {{-- SEO Card --}}
                <div class="admin-card" style="margin-bottom:24px;">
                    <div class="admin-card-header">
                        <h2><i class="fas fa-search" style="margin-right:8px; opacity:0.5;"></i>SEO Settings</h2>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-form-group">
                            <label class="admin-form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="admin-form-control" id="blogMetaTitle"
                                value="{{ old('meta_title') }}" maxlength="70">
                            <small style="color:var(--admin-text-muted);">Recommended: 50-60 characters</small>
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-form-label">Meta Description</label>
                            <textarea name="meta_description" class="admin-form-control" rows="3" id="blogMetaDesc"
                                maxlength="160">{{ old('meta_description') }}</textarea>
                            <small style="color:var(--admin-text-muted);">Recommended: 120-160 characters</small>
                        </div>
                        {{-- SERP Preview --}}
                        <div
                            style="margin-top:16px; padding:16px; background:var(--admin-bg-secondary); border-radius:10px; border:1px solid var(--admin-surface-border);">
                            <div
                                style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:var(--admin-text-muted); margin-bottom:10px; font-weight:600;">
                                <i class="fab fa-google" style="margin-right:4px;"></i> SERP Preview
                            </div>
                            <div id="serpPreview">
                                <div id="serpTitle"
                                    style="font-size:18px; color:#1a0dab; font-family:'Arial'; cursor:pointer; line-height:1.3;">
                                    Your blog title will appear here</div>
                                <div id="serpUrl" style="font-size:13px; color:#006621; font-family:'Arial'; margin:4px 0;">
                                    {{ url('/blog/') }}<span id="serpSlug">your-post-slug</span>
                                </div>
                                <div id="serpDesc"
                                    style="font-size:13px; color:#545454; font-family:'Arial'; line-height:1.4;">Your meta
                                    description will appear here. Write a compelling description to improve click-through
                                    rates.</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SEO Analyzer Card --}}
                <div class="admin-card" style="margin-bottom:24px;">
                    <div class="admin-card-header" style="display:flex; justify-content:space-between; align-items:center;">
                        <h2><i class="fas fa-chart-bar" style="margin-right:8px; opacity:0.5;"></i>SEO Analysis</h2>
                        <div id="seoScoreBadge" style="display:flex; align-items:center; gap:8px;">
                            <div id="seoScoreCircle"
                                style="width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; color:#fff; background:#d97706;">
                                0
                            </div>
                            <span style="font-size:13px; font-weight:500; color:var(--admin-text-muted);">/ 100</span>
                        </div>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-form-group" style="margin-bottom:16px;">
                            <label class="admin-form-label">Focus Keyword</label>
                            <input type="text" class="admin-form-control" id="focusKeyword"
                                placeholder="Enter your target keyword (e.g. best smartphones 2025)">
                        </div>
                        <div id="seoChecklist" style="display:flex; flex-direction:column; gap:8px;">
                            {{-- Checklist items will be populated by JS --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                {{-- Publish Card --}}
                <div class="admin-card" style="margin-bottom:24px;">
                    <div class="admin-card-header">
                        <h2><i class="fas fa-paper-plane" style="margin-right:8px; opacity:0.5;"></i>Publish</h2>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-form-group">
                            <label class="admin-form-label">Status <span style="color:#ef4444;">*</span></label>
                            <select name="status" class="admin-form-control">
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published
                                </option>
                            </select>
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-form-label">Category</label>
                            <select name="blog_category_id" class="admin-form-control">
                                <option value="">— No Category —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('blog_category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-admin-primary" style="width:100%;">
                            <i class="fas fa-save" style="margin-right:6px;"></i>Create Post
                        </button>
                    </div>
                </div>

                {{-- Featured Image Card (Media Library) --}}
                <div class="admin-card" style="margin-bottom:24px;">
                    <div class="admin-card-header">
                        <h2><i class="fas fa-image" style="margin-right:8px; opacity:0.5;"></i>Featured Image</h2>
                    </div>
                    <div class="admin-card-body">
                        <input type="hidden" name="featured_image_url" id="featuredImageUrl"
                            value="{{ old('featured_image_url') }}">
                        <div id="featuredImagePreview" style="margin-bottom:12px; display:none;">
                            <img id="featuredImageImg" src="" alt="Featured"
                                style="width:100%; max-height:200px; object-fit:cover; border-radius:8px; border:1px solid var(--admin-surface-border);">
                        </div>
                        <button type="button" class="btn-admin-primary" style="width:100%;"
                            onclick="openFeaturedImagePicker()">
                            <i class="fas fa-images" style="margin-right:6px;"></i>Select from Media Library
                        </button>
                        <button type="button" id="removeFeaturedBtn" class="btn-admin-sm btn-admin-danger"
                            style="width:100%; margin-top:8px; display:none;" onclick="removeFeaturedImage()">
                            <i class="fas fa-trash" style="margin-right:4px;"></i>Remove Image
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Featured Image Media Library Dialog --}}
    <div class="mks-dialog-overlay mks-media-overlay" id="featuredMediaDialog" style="display:none;">
        <div class="mks-dialog mks-media-dialog">
            <div class="mks-dialog-header">
                <h3><i class="fas fa-images"></i> Select Featured Image</h3>
                <button type="button" class="mks-dialog-close"
                    onclick="document.getElementById('featuredMediaDialog').style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mks-dialog-body">
                <div class="mks-media-tabs">
                    <button type="button" class="mks-media-tab active"
                        onclick="switchFeaturedTab('library')">Library</button>
                    <button type="button" class="mks-media-tab" onclick="switchFeaturedTab('upload')">Upload New</button>
                </div>
                <div class="mks-media-panel active" id="featuredLibraryPanel">
                    <div class="mks-media-grid" id="featuredMediaGrid">
                        <div style="text-align:center;padding:40px;color:var(--admin-text-muted);">
                            <i class="fas fa-spinner fa-spin" style="font-size:24px;"></i>
                        </div>
                    </div>
                </div>
                <div class="mks-media-panel" id="featuredUploadPanel" style="display:none;">
                    <div class="mks-media-upload-zone" id="featuredDropZone">
                        <i class="fas fa-cloud-upload-alt"
                            style="font-size:48px; color:var(--admin-accent); margin-bottom:12px;"></i>
                        <p style="font-weight:600; margin-bottom:4px;">Drag & drop image here</p>
                        <p style="color:var(--admin-text-muted); font-size:13px;">or click to browse</p>
                        <input type="file" id="featuredFileInput" accept="image/*" style="display:none;">
                    </div>
                </div>
            </div>
            <div class="mks-dialog-footer">
                <button type="button" class="btn-admin-primary" onclick="insertFeaturedImage()">
                    <i class="fas fa-check" style="margin-right:4px;"></i>Select Image
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // ====== Featured Image Media Library ======
        var selectedFeaturedUrl = '';

        function openFeaturedImagePicker() {
            var dialog = document.getElementById('featuredMediaDialog');
            // Move dialog to body to escape any stacking context
            if (dialog.parentElement !== document.body) {
                document.body.appendChild(dialog);
            }
            dialog.style.display = 'flex';
            loadFeaturedMedia();
            initFeaturedDropZone();
        }

        function switchFeaturedTab(tab) {
            var tabs = document.querySelectorAll('#featuredMediaDialog .mks-media-tab');
            tabs.forEach(function (t) { t.classList.remove('active'); });
            // Activate the correct tab button
            tabs.forEach(function (t) {
                if ((tab === 'library' && t.textContent.trim() === 'Library') ||
                    (tab === 'upload' && t.textContent.trim() === 'Upload New')) {
                    t.classList.add('active');
                }
            });
            document.getElementById('featuredLibraryPanel').style.display = tab === 'library' ? 'block' : 'none';
            document.getElementById('featuredUploadPanel').style.display = tab === 'upload' ? 'block' : 'none';
            if (tab === 'library') {
                loadFeaturedMedia();
            }
        }

        function loadFeaturedMedia() {
            var grid = document.getElementById('featuredMediaGrid');
            grid.innerHTML = '<div class="mks-media-empty"><i class="fas fa-spinner fa-spin" style="font-size:24px;display:block;margin-bottom:8px;"></i>Loading media...</div>';
            fetch('/dashboard/media/api')
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.files || data.files.length === 0) {
                        grid.innerHTML = '<div class="mks-media-empty"><i class="fas fa-images" style="font-size:32px;opacity:0.3;display:block;margin-bottom:12px;"></i>No media files yet.<br>Upload images using the Upload tab.</div>';
                        return;
                    }
                    grid.innerHTML = '';
                    data.files.forEach(function (file) {
                        var ext = file.name.split('.').pop().toLowerCase();
                        if (!['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext)) return;
                        var item = document.createElement('div');
                        item.className = 'mks-media-item';
                        item.setAttribute('data-url', file.url);
                        item.innerHTML = '<img src="' + file.url + '" alt="' + file.name + '" loading="lazy">';
                        item.addEventListener('click', function () {
                            grid.querySelectorAll('.mks-media-item').forEach(function (i) { i.classList.remove('selected'); });
                            this.classList.add('selected');
                            selectedFeaturedUrl = this.getAttribute('data-url');
                        });
                        grid.appendChild(item);
                    });
                });
        }

        function initFeaturedDropZone() {
            var dropZone = document.getElementById('featuredDropZone');
            var fileInput = document.getElementById('featuredFileInput');

            dropZone.onclick = function () { fileInput.click(); };
            dropZone.ondragover = function (e) { e.preventDefault(); this.classList.add('dragover'); };
            dropZone.ondragleave = function () { this.classList.remove('dragover'); };
            dropZone.ondrop = function (e) {
                e.preventDefault(); this.classList.remove('dragover');
                if (e.dataTransfer.files.length > 0) uploadFeaturedFiles(e.dataTransfer.files);
            };
            fileInput.onchange = function () { if (this.files.length > 0) uploadFeaturedFiles(this.files); };
        }

        function uploadFeaturedFiles(files) {
            var formData = new FormData();
            for (var i = 0; i < files.length; i++) formData.append('files[]', files[i]);
            var token = document.querySelector('meta[name="csrf-token"]').content;
            var dropZone = document.getElementById('featuredDropZone');
            dropZone.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:36px;color:var(--admin-accent);"></i><p style="margin-top:12px;">Uploading...</p>';
            fetch('/dashboard/media/upload', { method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    dropZone.innerHTML = '<i class="fas fa-check-circle" style="font-size:36px;color:#16a34a;"></i><p>Uploaded!</p>';
                    setTimeout(function () {
                        switchFeaturedTab('library');
                    }, 600);
                });
        }

        function insertFeaturedImage() {
            if (!selectedFeaturedUrl) { alert('Please select an image.'); return; }
            document.getElementById('featuredImageUrl').value = selectedFeaturedUrl;
            document.getElementById('featuredImageImg').src = selectedFeaturedUrl;
            document.getElementById('featuredImagePreview').style.display = 'block';
            document.getElementById('removeFeaturedBtn').style.display = 'block';
            document.getElementById('featuredMediaDialog').style.display = 'none';
            selectedFeaturedUrl = '';
            runSeoAnalysis();
        }

        function removeFeaturedImage() {
            document.getElementById('featuredImageUrl').value = '';
            document.getElementById('featuredImagePreview').style.display = 'none';
            document.getElementById('removeFeaturedBtn').style.display = 'none';
            runSeoAnalysis();
        }

        // ====== SERP Preview Live Update ======
        document.addEventListener('DOMContentLoaded', function () {
            var titleInput = document.getElementById('blogTitle');
            var metaTitleInput = document.getElementById('blogMetaTitle');
            var metaDescInput = document.getElementById('blogMetaDesc');
            var slugInput = document.getElementById('blogSlug');
            var slugManuallyEdited = false;

            // ====== Auto-slug generation ======
            function generateSlug(text) {
                return text.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '');
            }

            slugInput.addEventListener('input', function () {
                slugManuallyEdited = this.value.length > 0;
            });

            titleInput.addEventListener('input', function () {
                if (!slugManuallyEdited) {
                    slugInput.value = generateSlug(this.value);
                }
                updateSERP();
                runSeoAnalysis();
            });

            titleInput.addEventListener('paste', function () {
                var self = this;
                setTimeout(function () {
                    if (!slugManuallyEdited) {
                        slugInput.value = generateSlug(self.value);
                    }
                    updateSERP();
                    runSeoAnalysis();
                }, 0);
            });

            function updateSERP() {
                var title = metaTitleInput.value || titleInput.value || 'Your blog title';
                document.getElementById('serpTitle').textContent = title.substring(0, 70);
                document.getElementById('serpDesc').textContent = metaDescInput.value || 'Write a meta description to improve visibility in search results.';
                document.getElementById('serpSlug').textContent = slugInput.value || generateSlug(titleInput.value) || 'your-post-slug';
            }

            [metaTitleInput, metaDescInput, slugInput].forEach(function (el) {
                if (el) el.addEventListener('input', function () { updateSERP(); runSeoAnalysis(); });
            });

            // Also trigger on body change
            var bodyEditor = document.getElementById('blogBody_content');
            if (bodyEditor) {
                bodyEditor.addEventListener('input', function () { runSeoAnalysis(); });
            }

            var focusKw = document.getElementById('focusKeyword');
            if (focusKw) focusKw.addEventListener('input', function () { runSeoAnalysis(); });

            // ====== AJAX Form Submission ======
            var blogForm = document.getElementById('blogForm');
            blogForm.addEventListener('submit', function (e) {
                e.preventDefault();
                var submitBtn = blogForm.querySelector('button[type="submit"]');
                var originalHtml = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:6px;"></i>Saving...';

                // Sync editor content
                var hiddenBody = document.getElementById('blogBody_hidden');
                var contentDiv = document.getElementById('blogBody_content');
                if (hiddenBody && contentDiv) hiddenBody.value = contentDiv.innerHTML;

                var formData = new FormData(blogForm);
                var token = document.querySelector('meta[name="csrf-token"]').content;

                fetch(blogForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                })
                .then(function (r) { return r.json().then(function(d) { return {status: r.status, data: d}; }); })
                .then(function (res) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHtml;
                    if (res.data.success) {
                        showToast(res.data.message, 'success');
                        if (res.data.redirect) {
                            setTimeout(function() { window.location.href = res.data.redirect; }, 800);
                        }
                    } else if (res.data.errors) {
                        var msgs = Object.values(res.data.errors).flat().join('\n');
                        showToast(msgs, 'error');
                    } else if (res.data.message) {
                        showToast(res.data.message, 'error');
                    }
                })
                .catch(function (err) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHtml;
                    showToast('An error occurred. Please try again.', 'error');
                });
            });

            function showToast(msg, type) {
                var existing = document.getElementById('ajaxToast');
                if (existing) existing.remove();
                var toast = document.createElement('div');
                toast.id = 'ajaxToast';
                toast.style.cssText = 'position:fixed;top:24px;right:24px;z-index:999999;padding:14px 24px;border-radius:10px;font-size:14px;font-weight:500;color:#fff;box-shadow:0 8px 32px rgba(0,0,0,.18);transition:opacity .3s;';
                toast.style.background = type === 'success' ? '#16a34a' : '#ef4444';
                toast.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '" style="margin-right:8px;"></i>' + msg;
                document.body.appendChild(toast);
                setTimeout(function () { toast.style.opacity = '0'; setTimeout(function () { toast.remove(); }, 300); }, 4000);
            }
        });

        // ====== SEO Analyzer ======
        function runSeoAnalysis() {
            var title = (document.getElementById('blogTitle').value || '').trim();
            var metaTitle = (document.getElementById('blogMetaTitle').value || '').trim();
            var metaDesc = (document.getElementById('blogMetaDesc').value || '').trim();
            var slug = (document.getElementById('blogSlug').value || '').trim();
            var excerpt = (document.getElementById('blogExcerpt').value || '').trim();
            var bodyEl = document.getElementById('blogBody_content');
            var bodyText = bodyEl ? (bodyEl.innerText || '') : '';
            var bodyHtml = bodyEl ? (bodyEl.innerHTML || '') : '';
            var keyword = (document.getElementById('focusKeyword').value || '').trim().toLowerCase();
            var featuredImage = document.getElementById('featuredImageUrl').value;

            var checks = [];
            var score = 0;
            var maxScore = 0;

            function addCheck(label, passed, weight, tip) {
                maxScore += weight;
                if (passed) score += weight;
                checks.push({ label: label, passed: passed, tip: tip });
            }

            // ---- Title checks ----
            addCheck('Post title is present', title.length > 0, 5, 'Add a title for your blog post.');
            addCheck('Title length is optimal (30-70 chars)', title.length >= 30 && title.length <= 70, 5,
                'Title is ' + title.length + ' chars. Aim for 30-70 characters.');
            if (keyword) {
                addCheck('Focus keyword in title', title.toLowerCase().indexOf(keyword) !== -1, 8,
                    'Include "' + keyword + '" in your title.');
            }

            // ---- Meta Title ----
            addCheck('Meta title is set', metaTitle.length > 0, 5, 'Set a meta title for better SEO.');
            addCheck('Meta title length (50-60 chars)', metaTitle.length >= 50 && metaTitle.length <= 60, 5,
                'Meta title is ' + metaTitle.length + ' chars. Best is 50-60.');
            if (keyword) {
                addCheck('Focus keyword in meta title', metaTitle.toLowerCase().indexOf(keyword) !== -1, 6,
                    'Include "' + keyword + '" in the meta title.');
            }

            // ---- Meta Description ----
            addCheck('Meta description is set', metaDesc.length > 0, 5, 'Write a meta description.');
            addCheck('Meta description length (120-160 chars)', metaDesc.length >= 120 && metaDesc.length <= 160, 5,
                'Description is ' + metaDesc.length + ' chars. Aim for 120-160.');
            if (keyword) {
                addCheck('Focus keyword in meta description', metaDesc.toLowerCase().indexOf(keyword) !== -1, 5,
                    'Include "' + keyword + '" in the meta description.');
            }

            // ---- Slug ----
            var effectiveSlug = slug || title.toLowerCase().replace(/[^a-z0-9]+/g, '-');
            addCheck('URL slug is present', effectiveSlug.length > 0, 3, 'Set a readable URL slug.');
            if (keyword) {
                addCheck('Focus keyword in URL', effectiveSlug.indexOf(keyword.replace(/\s+/g, '-')) !== -1, 5,
                    'Include keyword in the URL slug.');
            }

            // ---- Content ----
            var wordCount = bodyText.trim().split(/\s+/).filter(function (w) { return w.length > 0; }).length;
            addCheck('Content has sufficient length (300+ words)', wordCount >= 300, 8,
                'Content has ' + wordCount + ' words. Aim for at least 300 words.');
            addCheck('Content is substantial (600+ words)', wordCount >= 600, 5,
                'For better SEO, write 600+ words. Currently: ' + wordCount + '.');

            if (keyword) {
                var keywordRegex = new RegExp(keyword, 'gi');
                var keywordOccurrences = (bodyText.match(keywordRegex) || []).length;
                var density = wordCount > 0 ? ((keywordOccurrences / wordCount) * 100).toFixed(1) : 0;
                addCheck('Keyword density (1-3%)', density >= 1 && density <= 3, 7,
                    'Keyword density is ' + density + '%. Ideal range is 1-3%.');
                addCheck('Keyword appears in first paragraph', bodyText.substring(0, 200).toLowerCase().indexOf(keyword) !== -1, 5,
                    'Use your focus keyword in the first paragraph.');
            }

            // ---- Headings ----
            var hasH2 = bodyHtml.indexOf('<h2') !== -1;
            var hasH3 = bodyHtml.indexOf('<h3') !== -1;
            addCheck('Content uses H2 headings', hasH2, 5, 'Break content with H2 subheadings.');
            addCheck('Content uses H3 headings', hasH3, 3, 'Use H3 for sub-sections.');

            // ---- Images ----
            var imgCount = (bodyHtml.match(/<img /gi) || []).length;
            addCheck('Content includes images', imgCount > 0, 5, 'Add relevant images to your content.');
            addCheck('Featured image is set', featuredImage.length > 0, 5, 'Set a featured image for the post.');

            // Check alt text on images
            var imgsWithoutAlt = (bodyHtml.match(/<img(?![^>]*alt=["'][^"']+["'])/gi) || []).length;
            if (imgCount > 0) {
                addCheck('All images have alt text', imgsWithoutAlt === 0, 4,
                    imgsWithoutAlt + ' image(s) missing alt text.');
            }

            // ---- Links ----
            var linkCount = (bodyHtml.match(/<a /gi) || []).length;
            addCheck('Content includes links', linkCount > 0, 3, 'Add internal or external links.');

            // ---- Excerpt ----
            addCheck('Excerpt is provided', excerpt.length > 0, 3, 'Write an excerpt summary for your post.');

            // ---- Readability ----
            var sentences = bodyText.split(/[.!?]+/).filter(function (s) { return s.trim().length > 0; });
            var avgSentenceLen = sentences.length > 0 ? Math.round(wordCount / sentences.length) : 0;
            if (sentences.length > 1) {
                addCheck('Sentence length is readable (< 25 words avg)', avgSentenceLen < 25, 4,
                    'Average sentence: ' + avgSentenceLen + ' words. Keep under 25.');
            }

            // ---- Calculate percentage score ----
            var pctScore = maxScore > 0 ? Math.round((score / maxScore) * 100) : 0;

            // ---- Render ----
            var circle = document.getElementById('seoScoreCircle');
            circle.textContent = pctScore;
            if (pctScore >= 80) { circle.style.background = '#16a34a'; }
            else if (pctScore >= 50) { circle.style.background = '#d97706'; }
            else { circle.style.background = '#ef4444'; }

            var checklist = document.getElementById('seoChecklist');
            checklist.innerHTML = '';
            checks.forEach(function (check) {
                var item = document.createElement('div');
                item.style.cssText = 'display:flex;align-items:flex-start;gap:10px;padding:10px 14px;border-radius:8px;background:var(--admin-bg-secondary);border:1px solid var(--admin-surface-border);';
                var icon = check.passed
                    ? '<i class="fas fa-check-circle" style="color:#16a34a;margin-top:2px;flex-shrink:0;"></i>'
                    : '<i class="fas fa-times-circle" style="color:#ef4444;margin-top:2px;flex-shrink:0;"></i>';
                var tipHtml = check.passed ? '' : '<div style="font-size:12px;color:var(--admin-text-muted);margin-top:2px;">' + check.tip + '</div>';
                item.innerHTML = icon + '<div><div style="font-size:13px;font-weight:500;">' + check.label + '</div>' + tipHtml + '</div>';
                checklist.appendChild(item);
            });
        }

        // Initial analysis
        document.addEventListener('DOMContentLoaded', function () { setTimeout(runSeoAnalysis, 500); });
    </script>
@endsection