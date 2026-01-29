<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'AI Interface')</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/app.css', 'resources/css/chat.css', 'resources/js/app.js'])

  @stack('styles')
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <button class="menu-toggle" id="menuToggle">
      <i class="fas fa-bars"></i>
    </button>
    <button class="history-toggle" id="historyToggle">
      <i class="far fa-rectangle-list"></i>
    </button>
  </div>

  <div class="sidebar-content">
    <button class="new-chat-btn">
      <i class="far fa-edit"></i>
      <span>New chat</span>
    </button>

    <button class="sidebar-item"><i class="fas fa-search"></i> <span>Search chats</span></button>
    <button class="sidebar-item"><i class="far fa-image"></i> <span>Images</span></button>
    <button class="sidebar-item"><i class="fas fa-th"></i> <span>Apps</span></button>
    <button class="sidebar-item"><i class="far fa-folder"></i> <span>Projects</span></button>

    <div class="sidebar-divider"></div>

    <div class="sidebar-section">
      <h3 class="section-title">Your chats</h3>
      <div class="chat-list">
        <button class="chat-item active">Đoạn chat 2 giải thích</button>
        <button class="chat-item">Assistant Response Clarification</button>
      </div>
    </div>
  </div>

  <div class="sidebar-footer">
    <div class="user-info">
      <div class="user-avatar"><i class="fas fa-user"></i></div>
      <div class="user-details">
        <span class="user-name">chat gpt</span>
        <span class="user-plan">Free</span>
      </div>
    </div>
    <button class="upgrade-btn">Upgrade</button>
  </div>
</aside>

<!-- Main Content -->
<main class="main-content">

  <!-- Top Bar -->
  <header class="top-bar">
    <button class="mobile-menu-btn" id="mobileMenuBtn">
      <i class="fas fa-bars"></i>
    </button>

    <div class="model-selector"></div>

    <div class="top-bar-actions">
      <button class="action-btn get-plus-btn">
        <i class="fas fa-plus"></i>
        <span>Get Plus</span>
        <i class="fas fa-times"></i>
      </button>
      <button class="action-btn share-btn">
        <i class="fas fa-arrow-up-from-bracket"></i>
        <span>Share</span>
      </button>
      <button class="action-btn more-btn">
        <i class="fas fa-ellipsis"></i>
      </button>
    </div>
  </header>

  {{-- 🔥 CHỈ PHẦN NÀY THAY ĐỔI --}}
  @yield('content')

</main>

<script src="{{ asset('js/jquery-4.0.0.min.js') }}"></script>

@stack('scripts')

</body>
</html>
