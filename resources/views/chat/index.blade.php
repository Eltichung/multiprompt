@extends('layouts.chat')

@section('title', 'Chat')

@section('content')
  <!-- Chat Area -->
  <div class="chat-container">
    <div class="chat-header"><h1 class="chat-title">Đoạn chat 2</h1></div>
    <div class="messages-area">
      <div class="message assistant-message">
        <div class="message-avatar"><i class="fas fa-robot"></i></div>
        <div class="message-content w-full max-w-[1192px] mx-auto grid grid-cols-1 min-[1000px]:grid-cols-2 gap-6 ">
          <div
            class="!p-4 rounded-xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
            <h1 class="text-lg font-bold mb-2">ChatGPT</h1>
            <div class="flex justify-end"><p class="!p-2 rounded-2xl bg-color">"Đoạn chat 2" là phần nào vậy ta? 🤔</p>
            </div>
            <div class="flex"><p>Bạn muốn:</p></div>
            <div class="flex"><p>Nói mình biết thêm một chút bối cảnh để mình làm đúng ý bạn nhá 🧑‍💻</p></div>
          </div>
          <div
            class="!p-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
            <h1 class="text-lg font-bold mb-2">Gemini</h1>
            <p>"Đoạn chat 3" là phần nào vậy ta? 🤔</p>
            <p>Bạn muốn:</p>
            <p>Nói mình biết thêm một chút bối cảnh để mình làm đúng ý bạn nhá 🧑‍💻</p></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Input Area -->
  <div class="input-area">
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
      <input type="text" class="message-input" placeholder="Ask anything">
      <button class="voice-btn"><i class="fas fa-microphone"></i></button>
      <button class="send-btn"><i class="fas fa-arrow-up"></i></button>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(function () {
      $.ajax({
        url: "{{ route('get.data.chat') }}",
        type: "POST",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          'X-Requested-With': 'XMLHttpRequest'
        },
        success(res) {
          console.log(res);
        }
      });
    });
  </script>
@endpush
