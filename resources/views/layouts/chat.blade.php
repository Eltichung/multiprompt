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
      <div id="chat-histories"></div>
      {{--      load data from server--}}
    </div>
  </div>

  <div class="sidebar-footer">
    <div class="user-info">
      <div class="user-avatar"><i class="fas fa-user"></i></div>
      <div class="user-details">
        <span class="user-name">{{$dataUser->name}}</span>
        <span class="user-plan">Free</span>
      </div>
    </div>
    <button class="upgrade-btn">Upgrade</button>
  </div>
</aside>

<main class="main-content">

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

  @yield('content')

</main>

<script src="{{ asset('js/jquery-4.0.0.min.js') }}"></script>
<script>
  $(document).ready(function () {
    loadChatHistories();
    renderNewChatTitle()
    initNewChatButton();
  });

  function loadChatHistories(selectedItemIndex = null) {

    $.ajax({
      url: "{{ route('get.data.chat.histories') }}",
      type: "POST",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'X-Requested-With': 'XMLHttpRequest'
      },
      success(res) {
        if (!res.success) return;

        const $container = $('#chat-histories');
        if (!$container.length) return;

        $container.empty(); //clear html

        res.data.forEach((item, index) => {
          $container.append(`
          <button
            class="chat-item ${index === selectedItemIndex ? 'active' : ''}"
            data-id="${item.id}"
          >
            ${item.content}
          </button>
        `);
        });
        initChatItemClick();
      },
      error(err) {
        console.error(err);
      }
    });
  }

  //Chat item click handler
  async function loadDataPrompt(promptId) {
    const $containerChat = $('#chat-content');
    $containerChat.empty();

    try {
      const res = await $.ajax({
        url: "{{ route('get.data.prompt') }}",
        type: "POST",
        data: {prompt_id: promptId},
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      if (!res?.data?.length) return;

      res.data.forEach(group => {
        renderProviderGroup(group, $containerChat);
      });

    } catch (error) {
      console.error('Load prompt error:', error);
    }
  }


  function renderProviderGroup(group, $containerChat) {
    const providerInfo = group[0]?.provider;
    const latestResult = group[0]?.latest_result;
    let messagesHtmlError = '';
    if(latestResult.error?.length){
      messagesHtmlError = `
      <div class="bg-yellow-50 border border-yellow-200 text-sm text-yellow-800 rounded-lg !p-2" role="alert" tabindex="-1" aria-labelledby="hs-with-description-label">
        <div class="flex">
          <div class="shrink-0 !p-2">
            <svg class="shrink-0 size-4 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
          </div>
          <div class="ms-4">
            <h3 id="hs-with-description-label" class="text-sm font-semibold">
              Lỗi gì rồi đại vương ơi....
            </h3>
            <div class="mt-1 text-sm text-yellow-800">
              ${latestResult.error}
            </div>
          </div>
        </div>
      </div> `
    }
    if (!providerInfo) return;

    const messagesHtml = group.map(item => {
      const isUser = item.role === 'user';
      const content = item.content.replace(/\n/g, '<br>');

      return `
      <div class="flex ${isUser ? 'justify-end' : ''} mb-2">
        <p class="${isUser ? '!p-2 rounded-2xl bg-color' : ''}">
          ${content}
        </p>
      </div>
    `;
    }).join('');

    $containerChat.append(`
    <div class="!p-4 rounded-xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition-shadow duration-200 mb-4">
      <h1 class="text-lg font-bold mb-2">${providerInfo.name}</h1>
      ${messagesHtml}

      <!-- Alert -->
      ${messagesHtmlError}
      <!-- End Alert -->
    </div>
  `);
  }

  function initChatItemClick() {
    const $chatItems = $('.chat-item');
    const $sidebar   = $('.sidebar');
    const $body      = $('body');

    if (!$chatItems.length) return;

    $chatItems.off('click').on('click', async function () {
      const $this = $(this);

      // active state
      $chatItems.removeClass('active');
      $this.addClass('active');

      const promptId = $this.data('id');

      showLoading();
      await loadDataPrompt(promptId);
      hideLoading();

      // mobile: close sidebar
      if (window.innerWidth <= 768) {
        $sidebar.removeClass('open');
        $body.removeClass('sidebar-open');
      }
    });
  }

  function renderNewChatTitle(title = 'Hôm nay bạn muốn làm gì?') {
    $('#chat-content')
      .empty()
      .append(`
      <h3 class="text-lg font-italic mb-2" id="title-new-chat">
        ${title}
      </h3>
    `);
  }
  function initNewChatButton() {
    const $btnNewChat = $('.new-chat-btn');
    if (!$btnNewChat.length) return;

    $btnNewChat.off('click').on('click', function () {
      renderNewChatTitle(title);
    });
  }

</script>
@stack('scripts')

</body>
</html>
