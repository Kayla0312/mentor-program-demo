/* eslint-disable no-restricted-syntax */
/* eslint-disable prefer-template */

const API_URL = 'https://api.twitch.tv/kraken';
const CLIENT_ID = '3258fgrzobyejiu6e513olu366wjxs';
// 實況頻道 UI
const TEMPLATE = `
  <a href="https://www.twitch.tv/$chanel" target="_blank">
    <div class="stream">
      <div class="stream__video">
        <img src="$img">
      </div>
      <div class="stream__data">
        <div class="stream__avatar">
          <img src="$logo" alt="">
        </div>
        <div class="stream__info">
          <div class="stream__title">$title</div>
          <div class="stream__channel">$chanel</div>
        </div>
      </div> 
    </div>
  </a>
`;

// 顯示 navbar 前五名遊戲名稱
getGmaes((games) => {
  for (const game of games) { 
    const element = document.createElement('li');
    element.innerText = game.game.name;
    document.querySelector('.navbar__list').appendChild(element);
  }
  changeName(games[0].game.name);
});

// navbar 點選到 li 項目時
document.querySelector('.navbar__list').addEventListener('click', (e) => {
  if (e.target.tagName.toLowerCase() === 'li') {
    const gameName = e.target.innerText; // 選到 li 遊戲名稱
    changeName(gameName);  // 執行 changeName function，參數：點到的遊戲名稱
  }
});

// 變更遊戲
function changeName(gameName) {
  document.querySelector('h1').innerText = gameName; // 替換掉頁面標題遊戲名稱
  document.querySelector('.streams').innerText = ''; // 先清空底下實況
  getStreams(gameName, function(streams){ // 執行 getStream，參數：遊戲名稱＆function
    for(const stream of streams) {
      appedStream(stream);
    }
  })
};

//新增遊戲頻道
function appedStream(stream) {
  const element = document.createElement('div');
  document.querySelector('.streams').appendChild(element);
  element.outerHTML = TEMPLATE
    .replace('$img', stream.preview.medium)
    .replace('$logo', stream.channel.logo)
    .replace('$title', stream.channel.status)
    .replace('$chanel', stream.channel.name)
}

//拿 api 資料：前五名的遊戲名稱
function getGmaes (callback) {
  const request = new XMLHttpRequest();
  request.open('GET', `${API_URL}/games/top?limit=5`, true);
  request.setRequestHeader('Client-ID', CLIENT_ID);
  request.setRequestHeader('Accept', 'application/vnd.twitchtv.v5+json');
  request.onload = () => {
    if (request.status >= 200 && request.status < 400) {
      const games = JSON.parse(request.response).top;
      callback(games);
    }
  };
  request.send();
}

//拿 api 資料：遊戲實況 
function getStreams(gameName, callback) {
  const request = new XMLHttpRequest();
  request.open('GET', `${API_URL}/streams/?game=` + encodeURIComponent(gameName), true);
  request.setRequestHeader('Client-ID', CLIENT_ID);
  request.setRequestHeader('Accept', 'application/vnd.twitchtv.v5+json');
  request.onload = () => {
    if (request.status >= 200 && request.status < 400) {
      const data = JSON.parse(request.response).streams;
      callback(data);
    }
  };
  request.send();
}

// 漢堡選單
const mobileMenu = document.querySelector('.mobile-menu');
const navbarList = document.querySelector('.navbar__list');
mobileMenu.addEventListener('click', () => {
  navbarList.classList.toggle('open');
  mobileMenu.classList.toggle('open');
});

