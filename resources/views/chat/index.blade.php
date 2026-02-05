@extends('layouts.chat')

@section('title', 'Chat')

@section('content')

  <!-- Chat Area -->
  <div class="chat-container">
    <div class="chat-header"><h1 class="chat-title">Chào mừng bạn đã đến với chúng tôi</h1></div>
    <div class="messages-area">
      <div class="message assistant-message">
        <div class="message-avatar"><i class="fas fa-robot"></i></div>
        <div class="message-content w-full max-w-[1192px] mx-auto grid grid-cols-1 min-[1000px]:grid-cols-2 gap-6 "
             id="chat-content">

        </div>
      </div>
      @include('components.loading')
      @include('components.loading-chat')
    </div>

    <!-- Input Area -->
    <div class=" input-area sticky bottom-0 w-full bg-white z-50" id="bottom">
      <ul class="inline-flex flex-col sm:flex-row w-auto text-sm font-medium text-gray-900
      rounded-lg select-none input-container" id="list-ai">

        <li class="w-full border-b sm:border-b-0 sm:border-r border-gray-500 min-w-[100px] max-w-[150px]">
          <label class="flex items-center gap-2 px-4 py-3 cursor-pointer">
            <input type="checkbox" id="check-all" class="w-4 h-4" checked>
            All
          </label>
        </li>

        @foreach ($dataListAIProvider as $value)
          <li class="w-full border-b sm:border-b-0 sm:border-r border-gray-500 min-w-[100px] max-w-[150px]">
            <label class="flex items-center gap-2 px-4 py-3 cursor-pointer">
              <input type="checkbox" class="w-4 h-4 check-item" value="{{$value->code}}">
              {{ $value->name }}
            </label>
          </li>
        @endforeach
      </ul>

      <div class="input-container">
        <button class="attach-btn"><i class="fas fa-plus"></i></button>
        <textarea
          class="flex-1 resize-none bg-transparent text-sm leading-6
           placeholder-gray-400
           min-h-[40px] max-h-[120px]
           py-2 focus:outline-none"
          placeholder="Ask anything" id="message-input"></textarea>
        <button class="voice-btn"><i class="fas fa-microphone"></i></button>
        <button class="send-btn"><i class="fas fa-arrow-up"></i></button>
      </div>
    </div>

    @endsection

    @push('scripts')
      <script>
        $(function () {
          loadChatHistories();
        });

        function sendMessage(dataPost) {

          return $.ajax({
            url: "{{ route('chat.send.message') }}",
            type: "POST",
            data: dataPost,
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
              'X-Requested-With': 'XMLHttpRequest'
            }
          });
        }

        $('.send-btn').on('click', async function () {
          let promptId = $('.chat-item.active').data('id') || ''
          //loading
          if (!promptId) {
            $('#chat-histories').prepend(`
          <button
            class="chat-item active"
          >
            ${$('#message-input').val()}
          </button>
        `);
            renderNewChatTitle('')
          }

          // getdata
          const checkedValues = $('.check-item:checked')
            .map(function () {
              return $(this).val();
            })
            .get();

          let dataPost = {
            'prompt': message,
            'providers': checkedValues,
          }
          if (prompt_id) {
            dataPost.prompt_id = prompt_id;
          }
          showLoadingChat();
          $('#message-input').val("");
          const res = await sendMessage(dataPost);
          if (!res?.data) return;

          //show new chat
          loadChatHistories(0)
          loadDataPrompt(res.data.prompt_id);
          hideLoadingChat()
        })
      </script>
  @endpush
