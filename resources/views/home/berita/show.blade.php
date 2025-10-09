@extends('home.layouts.app')

@section('title', $berita->judul . ' - ' . config('app.name'))

@section('home')
<div class="breadcrumb-banner-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-text">
                    <h1 class="text-center">Berita</h1>
                    <div class="breadcrumb-bar">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li>Berita</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="blog-detail-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <!-- Article Header -->
                <article class="article-main">
                    <header class="article-header">
                        <div class="article-meta">
                            <div class="meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <time datetime="{{ $berita->tanggal }}">
                                    {{ \Carbon\Carbon::parse($berita->tanggal)->format('d F, Y') }}
                                </time>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-user"></i>
                                <span>Admin</span>
                            </div>
                            {{-- <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ ceil(str_word_count(strip_tags($berita->isi)) / 200) }} menit baca</span>
                            </div> --}}
                        </div>

                        <h1 class="article-title">{{ $berita->judul }}</h1>

                        @if($berita->deskripsi)
                        <div class="article-excerpt">
                            {!! $berita->deskripsi !!}
                        </div>
                        @endif
                    </header>

                    <!-- Featured Image -->
                    <div class="article-image">
                        <img src="{{ Storage::url($berita->image) }}" alt="{{ $berita->judul }}"
                            class="rounded shadow-sm img-fluid">
                    </div>

                    <!-- Article Content -->
                    <div class="prose article-content">
                        {!! $berita->isi !!}
                    </div>

                    <!-- Article Footer -->
                    <footer class="article-footer">
                        <div class="share-section">
                            <h6>Bagikan artikel ini:</h6>
                            <div class="share-buttons">
                                <a href="#" class="share-btn facebook" onclick="shareOnFacebook()"
                                    title="Share on Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="share-btn twitter" onclick="shareOnTwitter()"
                                    title="Share on Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="share-btn whatsapp" onclick="shareOnWhatsApp()"
                                    title="Share on WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="#" class="share-btn linkedin" onclick="shareOnLinkedIn()"
                                    title="Share on LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <button class="share-btn copy" onclick="copyToClipboard()" title="Copy Link">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>
                        </div>
                    </footer>
                </article>

                <!-- Navigation -->
                <nav class="article-navigation">
                    <div class="nav-item nav-prev">
                        {{-- Uncomment when you have previous/next article functionality
                        @if(isset($previousBerita))
                        <a href="{{ route('berita.show', $previousBerita->id) }}" class="nav-link">
                            <div class="nav-direction">
                                <i class="fas fa-chevron-left"></i>
                                <span>Artikel Sebelumnya</span>
                            </div>
                            <div class="nav-title">{{ Str::limit($previousBerita->judul, 50) }}</div>
                        </a>
                        @endif
                        --}}
                    </div>
                    <div class="nav-item nav-next">
                        {{-- Uncomment when you have previous/next article functionality
                        @if(isset($nextBerita))
                        <a href="{{ route('berita.show', $nextBerita->id) }}" class="nav-link">
                            <div class="nav-direction">
                                <span>Artikel Selanjutnya</span>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                            <div class="nav-title">{{ Str::limit($nextBerita->judul, 50) }}</div>
                        </a>
                        @endif
                        --}}
                    </div>
                </nav>

                <!-- Related Posts Section -->
                {{-- Uncomment when you have related posts
                @if(isset($relatedBerita) && count($relatedBerita) > 0)
                <section class="related-posts">
                    <h3 class="section-title">Artikel Terkait</h3>
                    <div class="row">
                        @foreach($relatedBerita as $related)
                        <div class="mb-4 col-md-4">
                            <div class="related-card">
                                <div class="related-image">
                                    <a href="{{ route('berita.show', $related->id) }}">
                                        <img src="{{ Storage::url($related->image) }}" alt="{{ $related->judul }}"
                                            class="img-fluid">
                                    </a>
                                </div>
                                <div class="related-content">
                                    <div class="related-meta">
                                        <time>{{ \Carbon\Carbon::parse($related->tanggal)->format('d M, Y') }}</time>
                                    </div>
                                    <h5 class="related-title">
                                        <a href="{{ route('berita.show', $related->id) }}">
                                            {{ $related->judul }}
                                        </a>
                                    </h5>
                                    <p class="related-excerpt">
                                        {{ Str::limit(strip_tags($related->deskripsi), 100) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif
                --}}
            </div>
        </div>
    </div>
</div>

<style>
    /* Modern Breadcrumb Styling */
    .breadcrumb-banner-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem 0;
        position: relative;
        overflow: hidden;
    }

    .breadcrumb-banner-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
    }

    .breadcrumb-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: white;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .breadcrumb {
        justify-content: center;
        background: none;
        padding: 0;
        margin-bottom: 0;
    }

    .breadcrumb-item {
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
    }

    .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb-item a:hover {
        color: white;
        text-decoration: underline;
    }

    .breadcrumb-item.active {
        color: white;
    }

    /* Main Blog Section */
    .blog-detail-section {
        padding: 4rem 0;
        background-color: #f8fafc;
    }

    /* Article Styling */
    .article-main {
        background: white;
        border-radius: 16px;
        padding: 3rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .article-header {
        margin-bottom: 2rem;
    }

    .article-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .meta-item i {
        color: #3b82f6;
        font-size: 1rem;
    }

    .article-title {
        font-size: 2.25rem;
        font-weight: 800;
        line-height: 1.2;
        color: #1e293b;
        margin-bottom: 1rem;
    }

    .article-excerpt {
        font-size: 1.125rem;
        color: #64748b;
        line-height: 1.6;
        font-style: italic;
        border-left: 4px solid #3b82f6;
        padding-left: 1.5rem;
        margin: 1.5rem 0;
    }

    .article-image {
        margin: 2.5rem 0;
        text-align: center;
    }

    .article-image img {
        width: 100%;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    /* Enhanced Prose Styling */
    .prose {
        max-width: none;
        color: #374151;
        line-height: 1.8;
        font-size: 1.1rem;
    }

    .prose p {
        margin-bottom: 1.5rem;
        text-align: justify;
    }

    .prose h1,
    .prose h2,
    .prose h3,
    .prose h4,
    .prose h5,
    .prose h6 {
        color: #1e293b;
        font-weight: 700;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
    }

    .prose h2 {
        font-size: 1.875rem;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0.5rem;
    }

    .prose h3 {
        font-size: 1.5rem;
    }

    .prose h4 {
        font-size: 1.25rem;
    }

    .prose ul,
    .prose ol {
        margin: 1.5rem 0;
        padding-left: 2rem;
    }

    .prose li {
        margin-bottom: 0.75rem;
    }

    .prose blockquote {
        border-left: 4px solid #3b82f6;
        background: #f1f5f9;
        padding: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: #64748b;
        border-radius: 0 8px 8px 0;
    }

    .prose img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 2rem auto;
        display: block;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .prose a {
        color: #3b82f6;
        text-decoration: underline;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .prose a:hover {
        color: #1d4ed8;
    }

    /* Article Footer */
    .article-footer {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid #e2e8f0;
    }

    .share-section h6 {
        color: #374151;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .share-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .share-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        text-decoration: none;
        color: white;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .share-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .share-btn.facebook {
        background: #1877f2;
    }

    .share-btn.twitter {
        background: #1da1f2;
    }

    .share-btn.whatsapp {
        background: #25d366;
    }

    .share-btn.linkedin {
        background: #0077b5;
    }

    .share-btn.copy {
        background: #6b7280;
    }

    /* Article Navigation */
    .article-navigation {
        display: flex;
        justify-content: space-between;
        gap: 2rem;
        margin: 2rem 0;
    }

    .nav-item {
        flex: 1;
    }

    .nav-link {
        display: block;
        padding: 1.5rem;
        background: white;
        border-radius: 12px;
        text-decoration: none;
        color: #374151;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .nav-link:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        color: #374151;
        text-decoration: none;
    }

    .nav-direction {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .nav-prev .nav-direction {
        justify-content: flex-start;
    }

    .nav-next .nav-direction {
        justify-content: flex-end;
    }

    .nav-title {
        font-weight: 600;
        line-height: 1.4;
    }

    /* Related Posts */
    .related-posts {
        margin-top: 4rem;
    }

    .section-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 2rem;
        text-align: center;
    }

    .related-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        height: 100%;
    }

    .related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .related-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .related-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .related-card:hover .related-image img {
        transform: scale(1.1);
    }

    .related-content {
        padding: 1.5rem;
    }

    .related-meta {
        color: #64748b;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .related-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .related-title a {
        color: #1e293b;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .related-title a:hover {
        color: #3b82f6;
    }

    .related-excerpt {
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-title {
            font-size: 1.875rem;
        }

        .article-main {
            padding: 2rem 1.5rem;
        }

        .article-title {
            font-size: 1.875rem;
        }

        .article-meta {
            flex-direction: column;
            gap: 1rem;
        }

        .article-navigation {
            flex-direction: column;
        }

        .share-buttons {
            justify-content: center;
        }

        .prose {
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .blog-detail-section {
            padding: 2rem 0;
        }

        .breadcrumb-banner-modern {
            padding: 2rem 0;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .article-main {
            padding: 1.5rem 1rem;
        }

        .article-title {
            font-size: 1.5rem;
        }
    }
</style>

<script>
    // Share functionality
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${title}`, '_blank', 'width=600,height=400');
}

function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${title}`, '_blank', 'width=600,height=400');
}

function shareOnWhatsApp() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    window.open(`https://wa.me/?text=${title} ${url}`, '_blank');
}

function shareOnLinkedIn() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank', 'width=600,height=400');
}

function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        // Show success message
        const btn = document.querySelector('.share-btn.copy');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.style.background = '#10b981';

        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.style.background = '#6b7280';
        }, 2000);
    }).catch(function() {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = window.location.href;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Link berhasil disalin!');
    });
}

// Smooth scrolling for internal links
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endsection
