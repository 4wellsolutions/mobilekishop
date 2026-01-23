@extends('emails.template')
@section('content')
<style>
    .email-wrapper {
        max-width: 600px;
        margin: 20px auto;
        background: #ffffff;
        border: 1px solid #ddd;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        font-family: 'Arial', sans-serif;
    }
    h2{
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .email-header {
        background-color: #034f84;
        color: #ffffff;
        padding: 20px;
        text-align: center;
    }
    .email-header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: normal;
    }
    .email-content {
        padding: 20px;
        text-align: left;
    }
    .blog-post {
        margin-bottom: 30px;
        border: 1px solid #e7e7e7;
        overflow: hidden;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }
    .blog-post:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }
    .blog-post img {
        width: 100%;
        height: auto;
        display: block;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
    .blog-post .blog-info {
        padding: 15px;
    }
    .blog-title {
        color: #034f84;
        font-size: 18px;
        margin: 0;
        margin-bottom: 10px;
    }
    .read-more-button {
        display: block;
        width: fit-content;
        padding: 10px 20px;
        background-color: #0275d8;
        color: #ffffff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        text-align: center;
        margin: 0 auto 20px;
    }
    .read-more-button:hover {
        background-color: #035c9c;
    }
    .email-wrapper {
        max-width: 600px;
        margin: 20px auto;
        background: #ffffff;
        border: 1px solid #ddd;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        font-family: 'Arial', sans-serif;
    }

    .email-header {
        background-color: #ffffff; /* Changed to white as requested */
        color: #034f84;
        padding: 20px;
        text-align: center;
        border-bottom: 5px solid #0c75be; /* Added attractive border */
    }

    /* ... other styles ... */

    .blog-post img {
        width: 100%;
        height: auto;
        display: block;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        border-bottom: 1px solid #ddd; /* Add border to the bottom of the image if needed */
    }

    /* Ensure that images are contained within the border */
    img {
        max-width: 100%;
        height: auto;
        display: block;
    }
    .read-more-button {
        display: inline-block;
        padding: 10px 20px;
        background-image: linear-gradient(to right, #0275d8, #035c9c); /* Gradient background */
        color: #ffffff;
        text-decoration: none;
        border-radius: 25px; /* Rounded borders */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Subtle shadow */
        transition: all 0.3s ease;
        text-align: center;
        margin-top: 10px;
    }

    .read-more-button:hover,
    .read-more-button:focus {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Larger shadow on hover/focus */
        transform: translateY(-2px); /* Slight raise */
        background-image: linear-gradient(to right, #035c9c, #0275d8); /* Gradient inversion */
    }

    .disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
</style>
</head>
<body>
<div class="email-wrapper">
    <div class="email-header">
        <h1>Welcome to Mobile Ki Shop</h1>
    </div>
    
    <div class="email-content">
        <p>Check out the latest insights in mobile technology!</p>
        @if($blogs = json_decode(file_get_contents("https://mobilekishop.net/blog/wp-json/wp/v2/posts?per_page=3&_fields=yoast_head_json")))
        @foreach($blogs as $blog)
        <!-- Blog Post -->
        <div class="blog-post">
            @php
                $blog_title = isset($blog->yoast_head_json->title) ? $blog->yoast_head_json->title : 'Untitled';
            @endphp
            <img src="{{ isset($blog->yoast_head_json->og_image[0]->url) ? $blog->yoast_head_json->og_image[0]->url : '#' }}" alt="{{ Str::limit($blog_title, 65) }}" style="max-width: 100% !important;">
            <div class="blog-info">
                
                <h2 class="blog-title" style="margin-top: 5px; margin-bottom: 5px;">{{ Str::limit($blog_title, 65) }}</h2>
                @if(isset($blog->yoast_head_json->og_url))
                    <a href="{{ $blog->yoast_head_json->og_url }}"  style="display: inline-block; padding: 10px 20px; background-color: #0275d8; color: #ffffff; text-decoration: none; border-radius: 25px; transition: all 0.3s ease; text-align: center; margin-top: 10px; margin-bottom: 10px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);" onmouseover="this.style.backgroundColor='#035c9c';" onmouseout="this.style.backgroundColor='#0275d8';" role="button">Read More</a>

                @else
                    <span style="display: inline-block; padding: 10px 20px; background-color: #a0a0a0; color: #ffffff; text-decoration: none; border-radius: 25px; text-align: center; margin-top: 10px; margin-bottom: 10px; opacity: 0.6; cursor: not-allowed;" aria-disabled="true">Read More</span>

                @endif
            </div>
        </div>

        @endforeach
        @endif
        
        <!-- Call to Action -->
        <p>Don't miss out on the latest mobile reviews, guides, and tips.</p>
        <a href="https://mobilekishop.net/blog/" class="read-more-button">Visit Our Blog</a>
    </div>
</div>
@stop