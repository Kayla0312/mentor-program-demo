/* eslint prefer-destructuring: ["error", {VariableDeclarator: {object: false}}] */
window.onload = () => {
  document.querySelector('.btn-add').addEventListener('click', () => {
    // 特殊字元處理
    function escapeHtml(unsafe) {
      return unsafe
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }
    const value = document.querySelector('.add-new-input').value;
    if (!value) return;
    // 新增 todo div
    const div = document.createElement('div');
    div.classList.add('todo');
    div.innerHTML = `
          <input class="todo-checkbox" type="checkbox">
          <div class="todo-content">${escapeHtml(value)}</div>
          <div class="btn-delete">&#10005</div>
      `;
    document.querySelector('.todos').appendChild(div);
    // 清空 input 新增完後的值
    document.querySelector('.add-new-input').value = '';
    // add, delete 設定
    document.querySelector('.todos').addEventListener('click', (e) => {
      const { target } = e;
      if (target.classList.contains('btn-delete')) {
        target.parentNode.remove();
        return;
      }
      if (target.classList.contains('todo-checkbox')) {
        if (target.checked) {
          target.parentNode.classList.add('isCheck');
        } else {
          target.parentNode.classList.remove('isCheck');
        }
      }
    });
  });
};
