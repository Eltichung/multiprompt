<div id="loading-funny" class="hidden text-center py-6 text-gray-500 italic">
  🤔 Hệ thống đang suy nghĩ rất nghiêm túc...
</div>
<script>
  let loadingTimer = null;

  function showLoadingChat() {
    const messages = [
      '🤔 Hệ thống đang suy nghĩ rất nghiêm túc...',
      '🧠 Đang lục lại kiến thức từ kiếp trước...',
      '⌛ Chờ xíu nha, não đang khởi động...',
      '☕ Pha ly cà phê đã rồi trả lời cho tử tế...'
    ];

    let index = 0;
    const $loading = $('#loading-funny');

    $loading.removeClass('hidden').text(messages[index]);

    loadingTimer = setInterval(() => {
      index = (index + 1) % messages.length;
      $loading.fadeOut(150, () => {
        $loading.text(messages[index]).fadeIn(150);
      });
    }, 1800);
  }

  function hideLoadingChat() {
    clearInterval(loadingTimer);
    $('#loading-funny').addClass('hidden');
  }

</script>
