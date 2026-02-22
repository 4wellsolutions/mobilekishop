{{--
Custom HTML Editor Component
Usage: @include('includes.html-editor', ['name' => 'body', 'value' => $content, 'id' => 'myEditor'])
--}}
@php
    $editorId = $id ?? 'editor_' . uniqid();
    $editorName = $name ?? 'content';
    $editorValue = $value ?? '';
@endphp

<div class="mks-editor" id="{{ $editorId }}_wrapper">
    {{-- Toolbar --}}
    <div class="mks-editor-toolbar">
        {{-- History Group --}}
        <div class="mks-toolbar-group">
            <button type="button" class="mks-toolbar-btn" data-cmd="undo" title="Undo (Ctrl+Z)">
                <i class="fas fa-undo"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-cmd="redo" title="Redo (Ctrl+Y)">
                <i class="fas fa-redo"></i>
            </button>
        </div>

        <div class="mks-toolbar-divider"></div>

        {{-- Block Format --}}
        <div class="mks-toolbar-group">
            <select class="mks-toolbar-select" data-cmd="formatBlock" title="Block Format">
                <option value="">Format</option>
                <option value="p">Paragraph</option>
                <option value="h1">Heading 1</option>
                <option value="h2">Heading 2</option>
                <option value="h3">Heading 3</option>
                <option value="h4">Heading 4</option>
                <option value="h5">Heading 5</option>
                <option value="h6">Heading 6</option>
                <option value="pre">Code Block</option>
                <option value="blockquote">Blockquote</option>
            </select>
        </div>

        <div class="mks-toolbar-divider"></div>

        {{-- Font Size --}}
        <div class="mks-toolbar-group">
            <select class="mks-toolbar-select mks-font-size-select" data-cmd="fontSize" title="Font Size">
                <option value="">Size</option>
                <option value="1">Small</option>
                <option value="2">Normal</option>
                <option value="3">Medium</option>
                <option value="4">Large</option>
                <option value="5">X-Large</option>
                <option value="6">XX-Large</option>
                <option value="7">Huge</option>
            </select>
        </div>

        <div class="mks-toolbar-divider"></div>

        {{-- Text Style --}}
        <div class="mks-toolbar-group">
            <button type="button" class="mks-toolbar-btn" data-cmd="bold" title="Bold (Ctrl+B)">
                <i class="fas fa-bold"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-cmd="italic" title="Italic (Ctrl+I)">
                <i class="fas fa-italic"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-cmd="underline" title="Underline (Ctrl+U)">
                <i class="fas fa-underline"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-cmd="strikeThrough" title="Strikethrough">
                <i class="fas fa-strikethrough"></i>
            </button>
        </div>

        <div class="mks-toolbar-divider"></div>

        {{-- Colors --}}
        <div class="mks-toolbar-group">
            <div class="mks-color-wrapper">
                <button type="button" class="mks-toolbar-btn" title="Text Color">
                    <i class="fas fa-font"></i>
                    <span class="mks-color-indicator" style="background:#ef4444;"></span>
                </button>
                <input type="color" class="mks-color-input" data-cmd="foreColor" value="#ef4444">
            </div>
            <div class="mks-color-wrapper">
                <button type="button" class="mks-toolbar-btn" title="Highlight Color">
                    <i class="fas fa-highlighter"></i>
                    <span class="mks-color-indicator" style="background:#fbbf24;"></span>
                </button>
                <input type="color" class="mks-color-input" data-cmd="hiliteColor" value="#fbbf24">
            </div>
        </div>

        <div class="mks-toolbar-divider"></div>

        {{-- Alignment --}}
        <div class="mks-toolbar-group">
            <button type="button" class="mks-toolbar-btn" data-cmd="justifyLeft" title="Align Left">
                <i class="fas fa-align-left"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-cmd="justifyCenter" title="Align Center">
                <i class="fas fa-align-center"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-cmd="justifyRight" title="Align Right">
                <i class="fas fa-align-right"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-cmd="justifyFull" title="Justify">
                <i class="fas fa-align-justify"></i>
            </button>
        </div>

        <div class="mks-toolbar-divider"></div>

        {{-- Lists --}}
        <div class="mks-toolbar-group">
            <button type="button" class="mks-toolbar-btn" data-cmd="insertUnorderedList" title="Bullet List">
                <i class="fas fa-list-ul"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-cmd="insertOrderedList" title="Numbered List">
                <i class="fas fa-list-ol"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-cmd="indent" title="Indent">
                <i class="fas fa-indent"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-cmd="outdent" title="Outdent">
                <i class="fas fa-outdent"></i>
            </button>
        </div>

        <div class="mks-toolbar-divider"></div>

        {{-- Insert --}}
        <div class="mks-toolbar-group">
            <button type="button" class="mks-toolbar-btn" data-action="link" title="Insert Link">
                <i class="fas fa-link"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-action="unlink" title="Remove Link" data-cmd="unlink">
                <i class="fas fa-unlink"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-action="image" title="Insert Image from Media Library">
                <i class="fas fa-image"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-action="video" title="Embed Video">
                <i class="fas fa-video"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-action="table" title="Insert Table">
                <i class="fas fa-table"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-cmd="insertHorizontalRule" title="Horizontal Line">
                <i class="fas fa-minus"></i>
            </button>
        </div>

        <div class="mks-toolbar-divider"></div>

        {{-- Utility --}}
        <div class="mks-toolbar-group">
            <button type="button" class="mks-toolbar-btn" data-cmd="removeFormat" title="Clear Formatting">
                <i class="fas fa-eraser"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-action="source" title="Source Code">
                <i class="fas fa-code"></i>
            </button>
            <button type="button" class="mks-toolbar-btn" data-action="fullscreen" title="Fullscreen">
                <i class="fas fa-expand"></i>
            </button>
        </div>
    </div>

    {{-- Editor Area --}}
    <div class="mks-editor-content" contenteditable="true" id="{{ $editorId }}_content">{!! $editorValue !!}</div>

    {{-- Source Code Area (hidden) --}}
    <textarea class="mks-editor-source" id="{{ $editorId }}_source" style="display:none;"></textarea>

    {{-- Hidden input for form submission --}}
    <textarea name="{{ $editorName }}" id="{{ $editorId }}_hidden" style="display:none;">{!! $editorValue !!}</textarea>

    {{-- Word count --}}
    <div class="mks-editor-footer">
        <span class="mks-editor-wordcount" id="{{ $editorId }}_wordcount">0 words</span>
        <span class="mks-editor-charcount" id="{{ $editorId }}_charcount">0 characters</span>
    </div>
</div>

{{-- Link Dialog --}}
<div class="mks-dialog-overlay" id="{{ $editorId }}_linkDialog" style="display:none;">
    <div class="mks-dialog">
        <div class="mks-dialog-header">
            <h3><i class="fas fa-link"></i> Insert Link</h3>
            <button type="button" class="mks-dialog-close"
                onclick="document.getElementById('{{ $editorId }}_linkDialog').style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mks-dialog-body">
            <div class="admin-form-group">
                <label class="admin-form-label">URL</label>
                <input type="url" class="admin-form-control" id="{{ $editorId }}_linkUrl"
                    placeholder="https://example.com">
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">Text</label>
                <input type="text" class="admin-form-control" id="{{ $editorId }}_linkText" placeholder="Link text">
            </div>
            <div class="admin-form-group">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="checkbox" id="{{ $editorId }}_linkNewTab" checked> Open in new tab
                </label>
            </div>
        </div>
        <div class="mks-dialog-footer">
            <button type="button" class="btn-admin-primary" id="{{ $editorId }}_linkInsert">Insert Link</button>
        </div>
    </div>
</div>

{{-- Video Embed Dialog --}}
<div class="mks-dialog-overlay" id="{{ $editorId }}_videoDialog" style="display:none;">
    <div class="mks-dialog">
        <div class="mks-dialog-header">
            <h3><i class="fas fa-video"></i> Embed Video</h3>
            <button type="button" class="mks-dialog-close"
                onclick="document.getElementById('{{ $editorId }}_videoDialog').style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mks-dialog-body">
            <div class="admin-form-group">
                <label class="admin-form-label">YouTube / Vimeo URL</label>
                <input type="url" class="admin-form-control" id="{{ $editorId }}_videoUrl"
                    placeholder="https://youtube.com/watch?v=...">
            </div>
        </div>
        <div class="mks-dialog-footer">
            <button type="button" class="btn-admin-primary" id="{{ $editorId }}_videoInsert">Embed Video</button>
        </div>
    </div>
</div>

{{-- Table Dialog --}}
<div class="mks-dialog-overlay" id="{{ $editorId }}_tableDialog" style="display:none;">
    <div class="mks-dialog">
        <div class="mks-dialog-header">
            <h3><i class="fas fa-table"></i> Insert Table</h3>
            <button type="button" class="mks-dialog-close"
                onclick="document.getElementById('{{ $editorId }}_tableDialog').style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mks-dialog-body">
            <div class="row">
                <div class="col-6">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Rows</label>
                        <input type="number" class="admin-form-control" id="{{ $editorId }}_tableRows" value="3" min="1"
                            max="20">
                    </div>
                </div>
                <div class="col-6">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Columns</label>
                        <input type="number" class="admin-form-control" id="{{ $editorId }}_tableCols" value="3" min="1"
                            max="10">
                    </div>
                </div>
            </div>
        </div>
        <div class="mks-dialog-footer">
            <button type="button" class="btn-admin-primary" id="{{ $editorId }}_tableInsert">Insert Table</button>
        </div>
    </div>
</div>

{{-- Image / Media Library Dialog --}}
<div class="mks-dialog-overlay mks-media-overlay" id="{{ $editorId }}_mediaDialog" style="display:none;">
    <div class="mks-dialog mks-media-dialog">
        <div class="mks-dialog-header">
            <h3><i class="fas fa-images"></i> Media Library</h3>
            <button type="button" class="mks-dialog-close"
                onclick="document.getElementById('{{ $editorId }}_mediaDialog').style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mks-dialog-body">
            {{-- Upload Tab --}}
            <div class="mks-media-tabs">
                <button type="button" class="mks-media-tab active" data-tab="library">Library</button>
                <button type="button" class="mks-media-tab" data-tab="upload">Upload New</button>
                <button type="button" class="mks-media-tab" data-tab="url">From URL</button>
            </div>

            {{-- Library Panel --}}
            <div class="mks-media-panel active" data-panel="library">
                <div class="mks-media-grid" id="{{ $editorId }}_mediaGrid">
                    <div style="text-align:center; padding:40px; color:var(--admin-text-muted);">
                        <i class="fas fa-spinner fa-spin" style="font-size:24px;"></i>
                        <p style="margin-top:8px;">Loading media...</p>
                    </div>
                </div>
            </div>

            {{-- Upload Panel --}}
            <div class="mks-media-panel" data-panel="upload" style="display:none;">
                <div class="mks-media-upload-zone" id="{{ $editorId }}_dropZone">
                    <i class="fas fa-cloud-upload-alt"
                        style="font-size:48px; color:var(--admin-accent); margin-bottom:12px;"></i>
                    <p style="font-weight:600; margin-bottom:4px;">Drag & drop files here</p>
                    <p style="color:var(--admin-text-muted); font-size:13px;">or click to browse</p>
                    <input type="file" id="{{ $editorId }}_fileInput" accept="image/*" multiple style="display:none;">
                </div>
            </div>

            {{-- URL Panel --}}
            <div class="mks-media-panel" data-panel="url" style="display:none;">
                <div class="admin-form-group" style="margin-top:16px;">
                    <label class="admin-form-label">Image URL</label>
                    <input type="url" class="admin-form-control" id="{{ $editorId }}_imgUrl"
                        placeholder="https://example.com/image.jpg">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Alt Text</label>
                    <input type="text" class="admin-form-control" id="{{ $editorId }}_imgAlt"
                        placeholder="Image description">
                </div>
            </div>
        </div>
        <div class="mks-dialog-footer">
            <button type="button" class="btn-admin-primary" id="{{ $editorId }}_mediaInsert">Insert Image</button>
        </div>
    </div>
</div>