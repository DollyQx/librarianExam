{{--
  Reusable AdSense / Banner Placeholder Container Component.
  Insert future Google AdSense <ins class="adsbygoogle"> snippet inside this container.
  Keeps layout clean and structured without displaying fake ads or disrupting UX.
--}}
@props([
    'type' => 'horizontal', // horizontal, square, in-feed
    'class' => 'my-4'
])

<div class="ad-container-wrapper text-center {{ $class }}" aria-label="Educational Partner Section">
    <div class="ad-container bg-white border rounded-3 p-2 d-flex align-items-center justify-content-center shadow-sm" style="min-height: 90px; max-width: 100%; overflow: hidden;">
        <!-- Google AdSense Code Insertion Point -->
        <div class="text-muted extra-small">
            <i class="fas fa-bullhorn me-1 text-primary opacity-50"></i> Educational Resources & Announcements
        </div>
    </div>
</div>
