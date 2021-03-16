'use strict';

{
  const add = document.getElementById('add');
  const menu = document.getElementById('menu');
  const mask = document.querySelector('.mask');
  const window = document.querySelector('.window');
  const menw_window = document.querySelector('.menw_window');

  add.addEventListener('click', () => {
    mask.classList.add('show');
    window.classList.add('show');
  })

  menu.addEventListener('click', () => {
    mask.classList.add('show');
    menw_window.classList.add('show');
  })

  mask.addEventListener('click', () => {
    mask.classList.remove('show');
    window.classList.remove('show');
    menw_window.classList.remove('show')
  })
}