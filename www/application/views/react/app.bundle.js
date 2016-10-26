/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports, __webpack_require__) {

	var ChatModal = __webpack_require__(1);
	var Danmu = __webpack_require__(2);
	var Footer = __webpack_require__(3);
	var Gift = __webpack_require__(4);
	var GiftModal = __webpack_require__(5);
	var Header = __webpack_require__(6);
	var Love = __webpack_require__(7);
	var Message = __webpack_require__(8);
	var MessageModal = __webpack_require__(9);
	var UserModal = __webpack_require__(10);

	window.ReactUpdate = {
	    header: function (key, data) {
	        var state = {};
	        state[key] = data;
	        console.log(state);
	        Header.setState(state);
	    },
	    danmu: function (key, data) {
	        ReactDOM.render(React.createElement(Danmu, { data: data }), document.getElementById('danmu'));
	    },
	    userModal: function (key, data) {
	        var state = {};
	        state[key] = data;
	        console.log(state);
	        UserModal.setState(state);
	    }
	};

/***/ },
/* 1 */
/***/ function(module, exports) {

	var ChatModal = React.createClass({
	  displayName: "ChatModal",

	  render: function () {
	    return React.createElement(
	      "div",
	      { className: "diy-cryptolalia bg-white hide" },
	      React.createElement("div", { className: "diy-cryp-title" }),
	      React.createElement("div", { className: "diy-cryp-data scrollBottom" }),
	      React.createElement("div", { className: "brow-imgs hide" }),
	      React.createElement(
	        "div",
	        { className: "bg-gray" },
	        React.createElement(
	          "div",
	          { className: "fl-l margin user-voice" },
	          React.createElement("img", { className: "width-25 height-25", src: "http://moker.b0.upaiyun.com/%E7%9B%B4%E6%92%AD%EF%BC%8D%E5%AF%86%E8%AF%AD/%E7%9B%B4%E6%92%AD%EF%BC%8D%E5%AF%86%E8%AF%AD%EF%BC%88%E7%A7%81%E8%81%8A%EF%BC%89%E9%A1%B5%E9%9D%A2_13.png" })
	        ),
	        React.createElement("div", { className: "chat cryptolalia-chat bg-white", contentEditable: "true" }),
	        React.createElement(
	          "div",
	          { className: "brow-btn margin-top fl-r" },
	          React.createElement("img", { className: "width-25 height-25", src: "http://moker.b0.upaiyun.com/%E7%9B%B4%E6%92%AD%EF%BC%8D%E5%AF%86%E8%AF%AD/%E7%9B%B4%E6%92%AD%EF%BC%8D%E5%AF%86%E8%AF%AD%EF%BC%88%E7%A7%81%E8%81%8A%EF%BC%89%E9%A1%B5%E9%9D%A2_16.png" })
	        ),
	        React.createElement(
	          "div",
	          { className: "brow-btn margin-top fl-r" },
	          React.createElement("img", { className: "width-25 height-25", src: "http://moker.b0.upaiyun.com/%E7%9B%B4%E6%92%AD%EF%BC%8D%E5%AF%86%E8%AF%AD/%E7%9B%B4%E6%92%AD%EF%BC%8D%E5%AF%86%E8%AF%AD%EF%BC%88%E7%A7%81%E8%81%8A%EF%BC%89%E9%A1%B5%E9%9D%A2_10.png" })
	        )
	      )
	    );
	  }
	});
	ReactDOM.render(React.createElement(ChatModal, null), document.getElementById('chatModal'));

/***/ },
/* 2 */
/***/ function(module, exports) {

	var Danmu = React.createClass({
	  displayName: "Danmu",

	  render: function () {
	    return React.createElement("div", { className: "barrage-space" });
	  }
	});
	ReactDOM.render(React.createElement(Danmu, null), document.getElementById('danmu'));

/***/ },
/* 3 */
/***/ function(module, exports) {

	var Footer = React.createClass({
	  displayName: "Footer",

	  render: function () {
	    return React.createElement(
	      "div",
	      { className: "diy-footer" },
	      React.createElement("a", { className: "fl-l diy-img-user", href: "javascript:void(0);" }),
	      React.createElement(
	        "a",
	        { className: "fl-l diy-img-chat", href: "javascript:void(0);" },
	        "\u6211\u60F3\u8BF4..."
	      ),
	      React.createElement("a", { className: "fl-r diy-img-trophy", href: "javascript:void(0);" }),
	      React.createElement("a", { className: "fl-r diy-img-gift", href: "javascript:void(0);" })
	    );
	  }
	});
	ReactDOM.render(React.createElement(Footer, null), document.getElementById('footer'));

/***/ },
/* 4 */
/***/ function(module, exports) {

	var Gift = React.createClass({
	  displayName: "Gift",

	  render: function () {
	    return React.createElement("ul", { className: "diy-sys text-or scrollBottom" });
	  }
	});
	ReactDOM.render(React.createElement(Gift, null), document.getElementById('gift'));

/***/ },
/* 5 */
/***/ function(module, exports) {

	
	var GiftModal = React.createClass({
	  displayName: "GiftModal",

	  render: function () {
	    return React.createElement(
	      "div",
	      { className: "diy-gift hide" },
	      React.createElement(
	        "div",
	        { className: "prompt-box hide" },
	        React.createElement(
	          "p",
	          null,
	          "\u793C\u5238\u5728\u6709\u6548\u671F\u5185\u53EF\u5151\u6362\u5546\u5BB6\u5546\u54C1\uFF0C\u9664\u793C\u5238\u5916\uFF0C\u5176\u5B83\u793C\u7269\u8D60\u9001\u540E\uFF0C\u670D\u52A1\u5458\u4F1A\u4E3B\u52A8\u8D60\u9001\u5546\u54C1\u3002"
	        ),
	        React.createElement(
	          "a",
	          { href: "javascript:void(0);" },
	          "\u77E5\u9053\u4E86"
	        )
	      ),
	      React.createElement("ul", { className: "gifts" }),
	      React.createElement(
	        "div",
	        { "class": "nav-dot" },
	        React.createElement("a", { className: "dot dot-white" }),
	        React.createElement("a", { className: "dot dot-gray" })
	      ),
	      React.createElement(
	        "div",
	        { "class": "bottom-btns" },
	        React.createElement(
	          "a",
	          { className: "buy-coin", href: "javascript:void(0);" },
	          "\u5145\u503C\uFF1A",
	          React.createElement(
	            "span",
	            { className: "text-white" },
	            "100 MO\u5E01"
	          )
	        ),
	        React.createElement(
	          "a",
	          { className: "gift-send", href: "javascript:void(0);" },
	          "\u53D1\u9001"
	        )
	      )
	    );
	  }
	});
	ReactDOM.render(React.createElement(GiftModal, null), document.getElementById('giftModal'));

/***/ },
/* 6 */
/***/ function(module, exports) {

	var Header = React.createClass({
	  displayName: "Header",

	  searchUser: function () {
	    console.log('search');
	  },
	  getInitialState: function () {
	    return { singer: {}, users: [],
	      bar: {
	        bar_id: 1,
	        desk_id: 1,
	        barimg: "http://wx.qlogo.cn/mmopen/NGA89eK6LL5uBHOzpicFC5DJPam9hOI1fUH5JkhQWehicrsLeOHaaCIQ7icaMU31iavxnicsN2BbZSrtHUyW3TgaO7s0QSrkAtwXs/0",
	        name: "巴克酒吧",
	        singer: { "user_id": "65", "openid": "oCwFFwrDIiJvDq0lcmLcMLSsfZGU",
	          "nickname": "Belen\u4e13\u6ce8\u4e92\u8054\u7f51\u524d\u7aef", "sex": "1",
	          "headimg": "http:\/\/wx.qlogo.cn\/mmopen\/ajNVdqHZLLBZ1rcCv06KrTBWHQcPibgPO1Oa9uiatBqibbQIpHIZaWfQklfyvicicdObzE7El90pfTMibcChroP0qZVQ\/0", "barrage_alert": "1", "last_login_ip": "123.151.38.94", "recent_bar_id": "1", "phone": null, "realname": null, "role": "g", "status": "1", "liveness": 4, "love_count": null, "last_login_time": "1474442505", "ext1": null, "ext2": null, "type": null, "love": "242"
	        }
	      }
	    };
	  },
	  render: function () {
	    var users = this.state.users.map(function (user) {
	      return React.createElement(
	        "a",
	        { className: "fl-r", onClick: GUI.showUserInfo.bind(null, user) },
	        React.createElement("img", { className: "radius-circle width-30 height-30", src: user.headimg })
	      );
	    });
	    return React.createElement(
	      "div",
	      { className: "header" },
	      React.createElement(
	        "div",
	        { className: "hd-grid clearboth" },
	        React.createElement(
	          "div",
	          { className: "diy-user text-white hd-h4 fl-l" },
	          React.createElement(
	            "a",
	            { onClick: GUI.showUserInfo.bind(null, g_bar.singer) },
	            React.createElement("img", { className: "radius-circle width-30 height-30 fl-l", src: this.state.singer.headimg })
	          ),
	          React.createElement(
	            "span",
	            { className: "text-white margin-left name text-ellipsis" },
	            this.state.singer.nickname
	          ),
	          React.createElement("br", null),
	          React.createElement(
	            "span",
	            { className: "text-white margin-left fl-l" },
	            this.state.users.length,
	            "\u4EBA"
	          )
	        ),
	        React.createElement(
	          "div",
	          { className: "diy-online-user text-r fl-r margin-top", id: "ID_onlineUsers" },
	          React.createElement(
	            "a",
	            { className: "fl-r margin-small-left margin-right-15", onClick: this.searchUser },
	            React.createElement("img", { className: "search radius-circle", src: "http://moker.b0.upaiyun.com/\u964C\u5BA2-\u76F4\u64AD/\u964C\u5BA2\uFF0D\u76F4\u64AD_02.png" })
	          ),
	          React.createElement(
	            "ul",
	            { className: "diy-online-imgs", id: "ID_onlineHeaders" },
	            users
	          )
	        )
	      ),
	      React.createElement(
	        "div",
	        { className: "hd-grid" },
	        React.createElement(
	          "div",
	          { className: "fl-l margin-left-15 padding-little radius-rounded opacity" },
	          React.createElement(
	            "span",
	            { className: "hd-h4 text-pink margin-left" },
	            "\u7231\u5FC3\u503C"
	          ),
	          React.createElement(
	            "span",
	            { className: "hd-h4 text-white margin-left margin-right", id: "ID_singerLove" },
	            this.state.singer.love
	          )
	        )
	      ),
	      React.createElement(
	        "div",
	        { className: "hd-grid margin-top" },
	        React.createElement(
	          "span",
	          { className: "hd-h4 margin-left-15 text-white" },
	          "\u5546\u5BB6\u516C\u544A\uFF1A"
	        ),
	        React.createElement(
	          "span",
	          { className: "hd-h4 text-yellow" },
	          "\u5E86\u795D\u6B27\u6D32\u676F\u8D5B\u4E8B\uFF0C\u5168\u573A\u4F18\u60E0"
	        )
	      )
	    );
	  }
	});
	module.exports = ReactDOM.render(React.createElement(Header, null), document.getElementById('header'));

/***/ },
/* 7 */
/***/ function(module, exports) {

	var Love = React.createClass({
	  displayName: "Love",

	  render: function () {
	    return React.createElement(
	      "div",
	      { className: "diy-zan" },
	      React.createElement(
	        "div",
	        { id: "player-praises" },
	        React.createElement("div", { className: "bubble", id: "bubble_div" })
	      ),
	      React.createElement("img", { className: "diy-zan-btn", src: "http://moker.b0.upaiyun.com/\u964C\u5BA2-\u76F4\u64AD/love_01.png" })
	    );
	  }
	});
	ReactDOM.render(React.createElement(Love, null), document.getElementById('love'));

/***/ },
/* 8 */
/***/ function(module, exports) {

	var Message = React.createClass({
	  displayName: "Message",

	  render: function () {
	    return React.createElement("ul", { className: "diy-msg text-yew scrollBottom" });
	  }
	});
	ReactDOM.render(React.createElement(Message, null), document.getElementById('message'));

/***/ },
/* 9 */
/***/ function(module, exports) {

	var MessageModal = React.createClass({
	  displayName: "MessageModal",

	  render: function () {
	    return React.createElement(
	      "div",
	      { className: "diy-chat hide" },
	      React.createElement("div", { className: "brow-imgs hide" }),
	      React.createElement(
	        "div",
	        { className: "barrage-btn open" },
	        React.createElement(
	          "div",
	          null,
	          "\u5F39"
	        )
	      ),
	      React.createElement("div", { className: "user" }),
	      React.createElement("textarea", { className: "chat", rows: "1", placeholder: "\u6211\u60F3\u8BF4\u2026 40\u5B57\u8DB3\u4EE5\u8868\u8FBE" }),
	      React.createElement(
	        "div",
	        { className: "brow-btn" },
	        React.createElement("img", { height: "30", src: "http://moker.b0.upaiyun.com/%E7%9B%B4%E6%92%AD%EF%BC%8D%E5%AF%86%E8%AF%AD/%E7%9B%B4%E6%92%AD%EF%BC%8D%E5%AF%86%E8%AF%AD%EF%BC%88%E7%A7%81%E8%81%8A%EF%BC%89%E9%A1%B5%E9%9D%A2_16.png" })
	      ),
	      React.createElement(
	        "div",
	        { className: "sendmsg-btn" },
	        "\u53D1\u9001"
	      )
	    );
	  }
	});
	ReactDOM.render(React.createElement(MessageModal, null), document.getElementById('messageModal'));

/***/ },
/* 10 */
/***/ function(module, exports) {

	var UserModal = React.createClass({
	  displayName: "UserModal",

	  getInitialState: function () {
	    return { user: {
	        headimg: "http://wx.qlogo.cn/mmopen/ajNVdqHZLLBZ1rcCv06KrTBWHQcPibgPO1Oa9uiatBqibbQIpHIZaWfQklfyvicicdObzE7El90pfTMibcChroP0qZVQ/0",
	        liveness: 4,
	        love: "312",
	        money: null,
	        nickname: "Belen专注互联网前端",
	        role: "g",
	        roleWord: "客人",
	        sex: "1",
	        user_id: "65"
	      } };
	  },
	  render: function () {
	    return React.createElement(
	      "div",
	      { className: "diy-user-detail hide" },
	      React.createElement("img", { className: "headimg radius-circle", src: this.state.user.headimg }),
	      React.createElement(
	        "div",
	        { className: "nickname text-c hd-h3 text-black margin-bottom" },
	        this.state.user.nickname,
	        React.createElement(
	          "span",
	          { className: "collect" },
	          this.state.user.liveness
	        ),
	        React.createElement("img", { width: "18", className: "vertical-m", src: "http://moker.b0.upaiyun.com/\u5F39\u7A97/\u964C\u5BA2\uFF0D\u641C\u7D22\uFF08\u5F39\u7A97\uFF09_03.png" })
	      ),
	      React.createElement(
	        "div",
	        { className: "text-c text-gray hd-h6 margin-bottom-15" },
	        this.state.user.roleWord,
	        React.createElement(
	          "span",
	          { className: "margin-left hd-h6" },
	          "\u7231\u5FC3\u503C\uFF1A",
	          this.state.user.love
	        )
	      ),
	      React.createElement(
	        "div",
	        { className: "links" },
	        React.createElement(
	          "a",
	          { className: "hd-col-xs-e3 ta", href: "javascript:void(0);" },
	          "@TA"
	        ),
	        React.createElement(
	          "a",
	          { className: "hd-col-xs-e3 gift", href: "javascript:void(0);" },
	          React.createElement("img", { src: "http://moker.b0.upaiyun.com/\u5F39\u7A97/\u964C\u5BA2\uFF0D\u641C\u7D22\uFF08\u5F39\u7A97\uFF09_07.png" })
	        ),
	        React.createElement(
	          "a",
	          { className: "hd-col-xs-e3 priv", href: "javascript:void(0);" },
	          "\u5BC6\u8BED"
	        )
	      )
	    );
	  }
	});
	module.exports = ReactDOM.render(React.createElement(UserModal, null), document.getElementById('userModal'));

/***/ }
/******/ ]);