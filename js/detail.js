"use strict";

{
  const dele = document.getElementById('delete');
  const more_horiz = document.getElementById('more_horiz');
  const mask = document.querySelector('.mask');
  const window = document.querySelector('.window');

  more_horiz.addEventListener('click', () => {
    mask.classList.add('show');
    window.classList.add('show');
  })

  mask.addEventListener('click', () => {
    mask.classList.remove('show');
    window.classList.remove('show');
  })

  dele.addEventListener('click', e => {
    if(!confirm('一度削除した記事は元に戻せません。本当に削除しますか？')) {
      e.preventDefault();
      alert('削除をキャンセルしました');
      location.href = '../php/blog_top.php';
    } else {
      alert('削除しました');
    }
  })

}
