// Copyright (c) 2009, Baidu Inc. All rights reserved.
// 
// Licensed under the BSD License
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
// 
//      http:// tangram.baidu.com/license.html
// 
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS-IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
 /**
 * @namespace T Tangram七巧板
 * @name T
 * @version 1.5.2.1
*/

/**
 * 声明baidu包
 * @author: allstar, erik, meizz, berg
 */
var T,
    baidu = T = baidu || {version: "1.5.2.1"}; 

//提出guid，防止在与老版本Tangram混用时
//在下一行错误的修改window[undefined]
baidu.guid = "$BAIDU$";

//Tangram可能被放在闭包中
//一些页面级别唯一的属性，需要挂载在window[baidu.guid]上
baidu.$$ = window[baidu.guid] = window[baidu.guid] || {global:{}};

/**
 * 操作dom的方法
 * @namespace baidu.dom 
 */
baidu.dom = baidu.dom || {};


/**
 * 从文档中获取指定的DOM元素
 * @name baidu.dom.g
 * @function
 * @grammar baidu.dom.g(id)
 * @param {string|HTMLElement} id 元素的id或DOM元素.
 * @shortcut g,T.G
 * @meta standard
 * @see baidu.dom.q
 *
 * @return {HTMLElement|null} 获取的元素，查找不到时返回null,如果参数不合法，直接返回参数.
 */
baidu.dom.g = function(id) {
    if (!id) return null; //修改IE下baidu.dom.g(baidu.dom.g('dose_not_exist_id'))报错的bug，by Meizz, dengping
    if ('string' == typeof id || id instanceof String) {
        return document.getElementById(id);
    } else if (id.nodeName && (id.nodeType == 1 || id.nodeType == 9)) {
        return id;
    }
    return null;
};

// 声明快捷方法
baidu.g = baidu.G = baidu.dom.g;

/**
 * 获取目标元素所属的document对象
 * @name baidu.dom.getDocument
 * @function
 * @grammar baidu.dom.getDocument(element)
 * @param {HTMLElement|string} element 目标元素或目标元素的id
 * @meta standard
 * @see baidu.dom.getWindow
 *             
 * @returns {HTMLDocument} 目标元素所属的document对象
 */
baidu.dom.getDocument = function (element) {
    element = baidu.dom.g(element);
    return element.nodeType == 9 ? element : element.ownerDocument || element.document;
};


/**
 * 对语言层面的封装，包括类型判断、模块扩展、继承基类以及对象自定义事件的支持。
 * @namespace baidu.lang
 */
baidu.lang = baidu.lang || {};


/**
 * 判断目标参数是否string类型或String对象
 * @name baidu.lang.isString
 * @function
 * @grammar baidu.lang.isString(source)
 * @param {Any} source 目标参数
 * @shortcut isString
 * @meta standard
 * @see baidu.lang.isObject,baidu.lang.isNumber,baidu.lang.isArray,baidu.lang.isElement,baidu.lang.isBoolean,baidu.lang.isDate
 *             
 * @returns {boolean} 类型判断结果
 */
baidu.lang.isString = function (source) {
    return '[object String]' == Object.prototype.toString.call(source);
};

// 声明快捷方法
baidu.isString = baidu.lang.isString;


/**
 * 从文档中获取指定的DOM元素
 * **内部方法**
 * 
 * @param {string|HTMLElement} id 元素的id或DOM元素
 * @meta standard
 * @return {HTMLElement} DOM元素，如果不存在，返回null，如果参数不合法，直接返回参数
 */
baidu.dom._g = function (id) {
    if (baidu.lang.isString(id)) {
        return document.getElementById(id);
    }
    return id;
};

// 声明快捷方法
baidu._g = baidu.dom._g;



/**
 * 获取目标元素的computed style值。如果元素的样式值不能被浏览器计算，则会返回空字符串（IE）
 *
 * @author berg
 * @name baidu.dom.getComputedStyle
 * @function
 * @grammar baidu.dom.getComputedStyle(element, key)
 * @param {HTMLElement|string} element 目标元素或目标元素的id
 * @param {string} key 要获取的样式名
 *
 * @see baidu.dom.getStyle
 *             
 * @returns {string} 目标元素的computed style值
 */

baidu.dom.getComputedStyle = function(element, key){
    element = baidu.dom._g(element);
    var doc = baidu.dom.getDocument(element),
        styles;
    if (doc.defaultView && doc.defaultView.getComputedStyle) {
        styles = doc.defaultView.getComputedStyle(element, null);
        if (styles) {
            return styles[key] || styles.getPropertyValue(key);
        }
    }
    return ''; 
};

// 20111204 meizz   去掉一个无用的import baidu.browser.ie
/**
 * 提供给setStyle与getStyle使用
 */
baidu.dom._styleFixer = baidu.dom._styleFixer || {};

/**
 * 提供给setStyle与getStyle使用
 */
baidu.dom._styleFilter = baidu.dom._styleFilter || [];



/**
 * 为获取和设置样式的过滤器
 * @private
 * @meta standard
 */
baidu.dom._styleFilter.filter = function (key, value, method) {
    for (var i = 0, filters = baidu.dom._styleFilter, filter; filter = filters[i]; i++) {
        if (filter = filter[method]) {
            value = filter(key, value);
        }
    }

    return value;
};

/**
 * 操作字符串的方法
 * @namespace baidu.string
 */
baidu.string = baidu.string || {};


/**
 * 将目标字符串进行驼峰化处理
 * @name baidu.string.toCamelCase
 * @function
 * @grammar baidu.string.toCamelCase(source)
 * @param {string} source 目标字符串
 * @remark
 * 支持单词以“-_”分隔
 * @meta standard
 *             
 * @returns {string} 驼峰化处理后的字符串
 */
 
 //todo:考虑以后去掉下划线支持？
baidu.string.toCamelCase = function (source) {
    //提前判断，提高getStyle等的效率 thanks xianwei
    if (source.indexOf('-') < 0 && source.indexOf('_') < 0) {
        return source;
    }
    return source.replace(/[-_][^-_]/g, function (match) {
        return match.charAt(1).toUpperCase();
    });
};


/**
 * 获取目标元素的样式值
 * @name baidu.dom.getStyle
 * @function
 * @grammar baidu.dom.getStyle(element, key)
 * @param {HTMLElement|string} element 目标元素或目标元素的id
 * @param {string} key 要获取的样式名
 * @remark
 * 
 * 为了精简代码，本模块默认不对任何浏览器返回值进行归一化处理（如使用getStyle时，不同浏览器下可能返回rgb颜色或hex颜色），也不会修复浏览器的bug和差异性（如设置IE的float属性叫styleFloat，firefox则是cssFloat）。<br />
 * baidu.dom._styleFixer和baidu.dom._styleFilter可以为本模块提供支持。<br />
 * 其中_styleFilter能对颜色和px进行归一化处理，_styleFixer能对display，float，opacity，textOverflow的浏览器兼容性bug进行处理。	
 * @shortcut getStyle
 * @meta standard
 * @see baidu.dom.setStyle,baidu.dom.setStyles, baidu.dom.getComputedStyle
 *             
 * @returns {string} 目标元素的样式值
 */
// TODO
// 1. 无法解决px/em单位统一的问题（IE）
// 2. 无法解决样式值为非数字值的情况（medium等 IE）
baidu.dom.getStyle = function (element, key) {
    var dom = baidu.dom;

    element = dom.g(element);
    key = baidu.string.toCamelCase(key);
    //computed style, then cascaded style, then explicitly set style.
    var value = element.style[key] ||
                (element.currentStyle ? element.currentStyle[key] : "") || 
                dom.getComputedStyle(element, key);

    // 在取不到值的时候，用fixer进行修正
    if (!value || value == 'auto') {
        var fixer = dom._styleFixer[key];
        if(fixer){
            value = fixer.get ? fixer.get(element, key, value) : baidu.dom.getStyle(element, fixer);
        }
    }
    
    /* 检查结果过滤器 */
    if (fixer = dom._styleFilter) {
        value = fixer.filter(key, value, 'get');
    }

    return value;
};

// 声明快捷方法
baidu.getStyle = baidu.dom.getStyle;

/**
 * 判断浏览器类型和特性的属性
 * @namespace baidu.browser
 */
baidu.browser = baidu.browser || {};


//IE 8下，以documentMode为准
//在百度模板中，可能会有$，防止冲突，将$1 写成 \x241
/**
 * 判断是否为ie浏览器
 * @name baidu.browser.ie
 * @field
 * @grammar baidu.browser.ie
 * @returns {Number} IE版本号
 */
baidu.browser.ie = baidu.ie = /msie (\d+\.\d+)/i.test(navigator.userAgent) ? (document.documentMode || + RegExp['\x241']) : undefined;

/**
 * 判断是否为opera浏览器
 * @property opera opera版本号
 * @grammar baidu.browser.opera
 * @meta standard
 * @see baidu.browser.ie,baidu.browser.firefox,baidu.browser.safari,baidu.browser.chrome
 * @returns {Number} opera版本号
 */

/**
 * opera 从10开始不是用opera后面的字符串进行版本的判断
 * 在Browser identification最后添加Version + 数字进行版本标识
 * opera后面的数字保持在9.80不变
 */
baidu.browser.opera = /opera(\/| )(\d+(\.\d+)?)(.+?(version\/(\d+(\.\d+)?)))?/i.test(navigator.userAgent) ?  + ( RegExp["\x246"] || RegExp["\x242"] ) : undefined;

/**
 * 判断是否为webkit内核
 * @property isWebkit 
 * @grammar baidu.browser.isWebkit
 * @meta standard
 * @see baidu.browser.isGecko
 * @returns {Boolean} 布尔值
 */
baidu.browser.isWebkit = /webkit/i.test(navigator.userAgent);

/**
 * 判断是否为gecko内核
 * @property isGecko 
 * @grammar baidu.browser.isGecko
 * @meta standard
 * @see baidu.browser.isWebkit
 * @returns {Boolean} 布尔值
 */
baidu.browser.isGecko = /gecko/i.test(navigator.userAgent) && !/like gecko/i.test(navigator.userAgent);

/**
 * 判断是否严格标准的渲染模式
 * @property isStrict 
 * @grammar baidu.browser.isStrict
 * @meta standard
 * @returns {Boolean} 布尔值
 */
baidu.browser.isStrict = document.compatMode == "CSS1Compat";


/**
 * 获取目标元素相对于整个文档左上角的位置
 * @name baidu.dom.getPosition
 * @function
 * @grammar baidu.dom.getPosition(element)
 * @param {HTMLElement|string} element 目标元素或目标元素的id
 * @meta standard
 *             
 * @returns {Object} 目标元素的位置，键值为top和left的Object。
 */
baidu.dom.getPosition = function (element) {
    element = baidu.dom.g(element);
    var doc = baidu.dom.getDocument(element), 
        browser = baidu.browser,
        getStyle = baidu.dom.getStyle,
    // Gecko 1.9版本以下用getBoxObjectFor计算位置
    // 但是某些情况下是有bug的
    // 对于这些有bug的情况
    // 使用递归查找的方式
        BUGGY_GECKO_BOX_OBJECT = browser.isGecko > 0 && 
                                 doc.getBoxObjectFor &&
                                 getStyle(element, 'position') == 'absolute' &&
                                 (element.style.top === '' || element.style.left === ''),
        pos = {"left":0,"top":0},
        viewport = (browser.ie && !browser.isStrict) ? doc.body : doc.documentElement,
        parent,
        box;
    
    if(element == viewport){
        return pos;
    }


    if(element.getBoundingClientRect){ // IE and Gecko 1.9+
        
    	//当HTML或者BODY有border width时, 原生的getBoundingClientRect返回值是不符合预期的
    	//考虑到通常情况下 HTML和BODY的border只会设成0px,所以忽略该问题.
        box = element.getBoundingClientRect();

        pos.left = Math.floor(box.left) + Math.max(doc.documentElement.scrollLeft, doc.body.scrollLeft);
        pos.top  = Math.floor(box.top)  + Math.max(doc.documentElement.scrollTop,  doc.body.scrollTop);
	    
        // IE会给HTML元素添加一个border，默认是medium（2px）
        // 但是在IE 6 7 的怪异模式下，可以被html { border: 0; } 这条css规则覆盖
        // 在IE7的标准模式下，border永远是2px，这个值通过clientLeft 和 clientTop取得
        // 但是。。。在IE 6 7的怪异模式，如果用户使用css覆盖了默认的medium
        // clientTop和clientLeft不会更新
        pos.left -= doc.documentElement.clientLeft;
        pos.top  -= doc.documentElement.clientTop;
        
        var htmlDom = doc.body,
            // 在这里，不使用element.style.borderLeftWidth，只有computedStyle是可信的
            htmlBorderLeftWidth = parseInt(getStyle(htmlDom, 'borderLeftWidth')),
            htmlBorderTopWidth = parseInt(getStyle(htmlDom, 'borderTopWidth'));
        if(browser.ie && !browser.isStrict){
            pos.left -= isNaN(htmlBorderLeftWidth) ? 2 : htmlBorderLeftWidth;
            pos.top  -= isNaN(htmlBorderTopWidth) ? 2 : htmlBorderTopWidth;
        }
    /*
     * 因为firefox 3.6和4.0在特定页面下(场景待补充)都会出现1px偏移,所以暂时移除该逻辑分支
     * 如果 2.0版本时firefox仍存在问题,该逻辑分支将彻底移除. by rocy 2011-01-20
    } else if (doc.getBoxObjectFor && !BUGGY_GECKO_BOX_OBJECT){ // gecko 1.9-

        // 1.9以下的Gecko，会忽略ancestors的scroll值
        // https://bugzilla.mozilla.org/show_bug.cgi?id=328881 and
        // https://bugzilla.mozilla.org/show_bug.cgi?id=330619

        box = doc.getBoxObjectFor(element);
        var vpBox = doc.getBoxObjectFor(viewport);
        pos.left = box.screenX - vpBox.screenX;
        pos.top  = box.screenY - vpBox.screenY;
        */
    } else { // safari/opera/firefox
        parent = element;

        do {
            pos.left += parent.offsetLeft;
            pos.top  += parent.offsetTop;
      
            // safari里面，如果遍历到了一个fixed的元素，后面的offset都不准了
            if (browser.isWebkit > 0 && getStyle(parent, 'position') == 'fixed') {
                pos.left += doc.body.scrollLeft;
                pos.top  += doc.body.scrollTop;
                break;
            }
            
            parent = parent.offsetParent;
        } while (parent && parent != element);

        // 对body offsetTop的修正
        if(browser.opera > 0 || (browser.isWebkit > 0 && getStyle(element, 'position') == 'absolute')){
            pos.top  -= doc.body.offsetTop;
        }

        // 计算除了body的scroll
        parent = element.offsetParent;
        while (parent && parent != doc.body) {
            pos.left -= parent.scrollLeft;
            // see https://bugs.opera.com/show_bug.cgi?id=249965
//            if (!b.opera || parent.tagName != 'TR') {
            if (!browser.opera || parent.tagName != 'TR') {
                pos.top -= parent.scrollTop;
            }
            parent = parent.offsetParent;
        }
    }

    return pos;
};

/**
 * 从DOM树上移除目标元素
 * @name baidu.dom.remove
 * @function
 * @grammar baidu.dom.remove(element)
 * @param {HTMLElement|string} element 需要移除的元素或元素的id
 * @remark
 * <b>注意：</b>对于移除的dom元素，IE下会释放该元素的空间，继续使用该元素的引用进行操作将会引发不可预料的问题。
 * @meta standard
 */
baidu.dom.remove = function (element) {
    element = baidu.dom._g(element);
	var tmpEl = element.parentNode;
    //去掉了对ie下的特殊处理：创建一个div，appendChild，然后div.innerHTML = ""
    tmpEl && tmpEl.removeChild(element);
};

/**
 * 删除目标字符串两端的空白字符
 * @name baidu.string.trim
 * @function
 * @grammar baidu.string.trim(source)
 * @param {string} source 目标字符串
 * @remark
 * 不支持删除单侧空白字符
 * @shortcut trim
 * @meta standard
 *             
 * @returns {string} 删除两端空白字符后的字符串
 */

(function () {
    var trimer = new RegExp("(^[\\s\\t\\xa0\\u3000]+)|([\\u3000\\xa0\\s\\t]+\x24)", "g");
    
    baidu.string.trim = function (source) {
        return String(source)
                .replace(trimer, "");
    };
})();

// 声明快捷方法
baidu.trim = baidu.string.trim;


/**
 * 为目标元素添加className
 * @name baidu.dom.addClass
 * @function
 * @grammar baidu.dom.addClass(element, className)
 * @param {HTMLElement|string} element 目标元素或目标元素的id
 * @param {string} className 要添加的className，允许同时添加多个class，中间使用空白符分隔
 * @remark
 * 使用者应保证提供的className合法性，不应包含不合法字符，className合法字符参考：http://www.w3.org/TR/CSS2/syndata.html。
 * @shortcut addClass
 * @meta standard
 * @see baidu.dom.removeClass
 * 	
 * 	            
 * @returns {HTMLElement} 目标元素
 */
baidu.dom.addClass = function (element, className) {
    element = baidu.dom.g(element);
    var classArray = className.split(/\s+/),
        result = element.className,
        classMatch = " " + result + " ",
        i = 0,
        l = classArray.length;

    for (; i < l; i++){
         if ( classMatch.indexOf( " " + classArray[i] + " " ) < 0 ) {
             result += (result ? ' ' : '') + classArray[i];
         }
    }

    element.className = result;
    return element;
};

// 声明快捷方法
baidu.addClass = baidu.dom.addClass;

/**
 * 移除目标元素的className
 * @name baidu.dom.removeClass
 * @function
 * @grammar baidu.dom.removeClass(element, className)
 * @param {HTMLElement|string} element 目标元素或目标元素的id
 * @param {string} className 要移除的className，允许同时移除多个class，中间使用空白符分隔
 * @remark
 * 使用者应保证提供的className合法性，不应包含不合法字符，className合法字符参考：http://www.w3.org/TR/CSS2/syndata.html。
 * @shortcut removeClass
 * @meta standard
 * @see baidu.dom.addClass
 *             
 * @returns {HTMLElement} 目标元素
 */
baidu.dom.removeClass = function (element, className) {
    element = baidu.dom.g(element);

    var oldClasses = element.className.split(/\s+/),
        newClasses = className.split(/\s+/),
        lenOld,
        lenDel = newClasses.length,
        j,
        i = 0;
    //考虑到同时删除多个className的应用场景概率较低,故放弃进一步性能优化 
    // by rocy @1.3.4
    for (; i < lenDel; ++i){
        for(j = 0, lenOld = oldClasses.length; j < lenOld; ++j){
            if(oldClasses[j] == newClasses[i]){
            	oldClasses.splice(j, 1);
            	break;
            }
        }
    }
    element.className = oldClasses.join(' ');
    return element;
};

// 声明快捷方法
baidu.removeClass = baidu.dom.removeClass;

/**
 * 判断元素是否拥有指定的className
 * @name baidu.dom.hasClass
 * @function
 * @grammar baidu.dom.hasClass(element, className)
 * @param {HTMLElement|string} element 目标元素或目标元素的id
 * @param {string} className 要判断的className，可以是用空格拼接的多个className
 * @version 1.2
 * @remark
 * 对于参数className，支持空格分隔的多个className
 * @see baidu.dom.addClass, baidu.dom.removeClass
 * @meta standard
 * @returns {Boolean} 是否拥有指定的className，如果要查询的classname有一个或多个不在元素的className中，返回false
 */
baidu.dom.hasClass = function (element, className) {
    element = baidu.dom.g(element);

    // 对于 textNode 节点来说没有 className
    if(!element || !element.className) return false;

    var classArray = baidu.string.trim(className).split(/\s+/), 
        len = classArray.length;

    className = element.className.split(/\s+/).join(" ");

    while (len--) {
        if(!(new RegExp("(^| )" + classArray[len] + "( |\x24)")).test(className)){
            return false;
        }
    }
    return true;
};


/**
 * 屏蔽浏览器差异性的事件封装
 * @namespace baidu.event
 * @property target 	事件的触发元素
 * @property pageX 		鼠标事件的鼠标x坐标
 * @property pageY 		鼠标事件的鼠标y坐标
 * @property keyCode 	键盘事件的键值
 */
baidu.event = baidu.event || {};


/**
 * 阻止事件传播
 * @name baidu.event.stopPropagation
 * @function
 * @grammar baidu.event.stopPropagation(event)
 * @param {Event} event 事件对象
 * @see baidu.event.stop,baidu.event.preventDefault
 */
baidu.event.stopPropagation = function (event) {
   if (event.stopPropagation) {
       event.stopPropagation();
   } else {
       event.cancelBubble = true;
   }
};

/**
 * 阻止事件的默认行为
 * @name baidu.event.preventDefault
 * @function
 * @grammar baidu.event.preventDefault(event)
 * @param {Event} event 事件对象
 * @meta standard
 * @see baidu.event.stop,baidu.event.stopPropagation
 */
baidu.event.preventDefault = function (event) {
   if (event.preventDefault) {
       event.preventDefault();
   } else {
       event.returnValue = false;
   }
};


/**
 * 停止事件
 * @name baidu.event.stop
 * @function
 * @grammar baidu.event.stop(event)
 * @param {Event} event 事件对象
 * @see baidu.event.stopPropagation,baidu.event.preventDefault
 */
baidu.event.stop = function (event) {
    var e = baidu.event;
    e.stopPropagation(event);
    e.preventDefault(event);
};




/**
 * 操作数组的方法
 * @namespace baidu.array
 */

baidu.array = baidu.array || {};


/**
 * 遍历数组中所有元素
 * @name baidu.array.each
 * @function
 * @grammar baidu.array.each(source, iterator[, thisObject])
 * @param {Array} source 需要遍历的数组
 * @param {Function} iterator 对每个数组元素进行调用的函数，该函数有两个参数，第一个为数组元素，第二个为数组索引值，function (item, index)。
 * @param {Object} [thisObject] 函数调用时的this指针，如果没有此参数，默认是当前遍历的数组
 * @remark
 * each方法不支持对Object的遍历,对Object的遍历使用baidu.object.each 。
 * @shortcut each
 * @meta standard
 *             
 * @returns {Array} 遍历的数组
 */
 
baidu.each = baidu.array.forEach = baidu.array.each = function (source, iterator, thisObject) {
    var returnValue, item, i, len = source.length;
    
    if ('function' == typeof iterator) {
        for (i = 0; i < len; i++) {
            item = source[i];
            //TODO
            //此处实现和标准不符合，标准中是这样说的：
            //If a thisObject parameter is provided to forEach, it will be used as the this for each invocation of the callback. If it is not provided, or is null, the global object associated with callback is used instead.
            returnValue = iterator.call(thisObject || source, item, i);
    
            if (returnValue === false) {
                break;
            }
        }
    }
    return source;
};

/**
 * 查询数组中指定元素的索引位置
 * @name baidu.array.indexOf
 * @function
 * @grammar baidu.array.indexOf(source, match[, fromIndex])
 * @param {Array} source 需要查询的数组
 * @param {Any} match 查询项
 * @param {number} [fromIndex] 查询的起始位索引位置，如果为负数，则从source.length+fromIndex往后开始查找
 * @see baidu.array.find,baidu.array.lastIndexOf
 *             
 * @returns {number} 指定元素的索引位置，查询不到时返回-1
 */
baidu.array.indexOf = function (source, match, fromIndex) {
    var len = source.length,
        iterator = match;
        
    fromIndex = fromIndex | 0;
    if(fromIndex < 0){//小于0
        fromIndex = Math.max(0, len + fromIndex)
    }
    for ( ; fromIndex < len; fromIndex++) {
        if(fromIndex in source && source[fromIndex] === match) {
            return fromIndex;
        }
    }
    
    return -1;
};


/**
 * 判断一个数组中是否包含给定元素
 * @name baidu.array.contains
 * @function
 * @grammar baidu.array.contains(source, obj)
 * @param {Array} source 需要判断的数组.
 * @param {Any} obj 要查找的元素.
 * @return {boolean} 判断结果.
 * @author berg
 */
baidu.array.contains = function(source, obj) {
    return (baidu.array.indexOf(source, obj) >= 0);
};



/**
 * 对目标字符串进行格式化
 * @name baidu.string.format
 * @function
 * @grammar baidu.string.format(source, opts)
 * @param {string} source 目标字符串
 * @param {Object|string...} opts 提供相应数据的对象或多个字符串
 * @remark
 * 
opts参数为“Object”时，替换目标字符串中的#{property name}部分。<br>
opts为“string...”时，替换目标字符串中的#{0}、#{1}...部分。
		
 * @shortcut format
 * @meta standard
 *             
 * @returns {string} 格式化后的字符串
 */
baidu.string.format = function (source, opts) {
    source = String(source);
    var data = Array.prototype.slice.call(arguments,1), toString = Object.prototype.toString;
    if(data.length){
	    data = data.length == 1 ? 
	    	/* ie 下 Object.prototype.toString.call(null) == '[object Object]' */
	    	(opts !== null && (/\[object Array\]|\[object Object\]/.test(toString.call(opts))) ? opts : data) 
	    	: data;
    	return source.replace(/#\{(.+?)\}/g, function (match, key){
	    	var replacer = data[key];
	    	// chrome 下 typeof /a/ == 'function'
	    	if('[object Function]' == toString.call(replacer)){
	    		replacer = replacer(key);
	    	}
	    	return ('undefined' == typeof replacer ? '' : replacer);
    	});
    }
    return source;
};

// 声明快捷方法
baidu.format = baidu.string.format;

/**
 * 操作原生对象的方法
 * @namespace baidu.object
 */
baidu.object = baidu.object || {};


/**
 * 将源对象的所有属性拷贝到目标对象中
 * @author erik
 * @name baidu.object.extend
 * @function
 * @grammar baidu.object.extend(target, source)
 * @param {Object} target 目标对象
 * @param {Object} source 源对象
 * @see baidu.array.merge
 * @remark
 * 
1.目标对象中，与源对象key相同的成员将会被覆盖。<br>
2.源对象的prototype成员不会拷贝。
		
 * @shortcut extend
 * @meta standard
 *             
 * @returns {Object} 目标对象
 */
baidu.extend =
baidu.object.extend = function (target, source) {
    for (var p in source) {
        if (source.hasOwnProperty(p)) {
            target[p] = source[p];
        }
    }
    
    return target;
};



/**
 * 返回一个当前页面的唯一标识字符串。
 * @name baidu.lang.guid
 * @function
 * @grammar baidu.lang.guid()
 * @version 1.1.1
 * @meta standard
 *             
 * @returns {String} 当前页面的唯一标识字符串
 */
baidu.lang.guid = function() {
    return "TANGRAM$" + baidu.$$._counter ++;
};

//不直接使用window，可以提高3倍左右性能
baidu.$$._counter = baidu.$$._counter || 1;


// 20111129	meizz	去除 _counter.toString(36) 这步运算，节约计算量

/**
 * Tangram继承机制提供的一个基类，用户可以通过继承baidu.lang.Class来获取它的属性及方法。
 * @class
 * @name 	baidu.lang.Class
 * @grammar baidu.lang.Class(guid)
 * @param 	{string}	guid	对象的唯一标识
 * @meta standard
 * @remark baidu.lang.Class和它的子类的实例均包含一个全局唯一的标识guid。guid是在构造函数中生成的，因此，继承自baidu.lang.Class的类应该直接或者间接调用它的构造函数。<br>baidu.lang.Class的构造函数中产生guid的方式可以保证guid的唯一性，及每个实例都有一个全局唯一的guid。
 * @meta standard
 * @see baidu.lang.inherits,baidu.lang.Event
 */
baidu.lang.Class = function() {
    this.guid = baidu.lang.guid();

    !this.__decontrolled && (baidu.$$._instances[this.guid] = this);
};

baidu.$$._instances = baidu.$$._instances || {};

/**
 * 释放对象所持有的资源，主要是自定义事件。
 * @name dispose
 * @grammar obj.dispose()
 * TODO: 将_listeners中绑定的事件剔除掉
 */
baidu.lang.Class.prototype.dispose = function(){
    delete baidu.$$._instances[this.guid];

    // this.__listeners && (for (var i in this.__listeners) delete this.__listeners[i]);

    for(var property in this){
        typeof this[property] != "function" && delete this[property];
    }
    this.disposed = true;   // 20100716
};

/**
 * 重载了默认的toString方法，使得返回信息更加准确一些。
 * 20111219 meizz 为支持老版本的className属性，以后统一改成 __type
 * @return {string} 对象的String表示形式
 */
baidu.lang.Class.prototype.toString = function(){
    return "[object " + (this.__type || this._className || "Object") + "]";
};

/**
 * 按唯一标识guid字符串取得实例对象
 *
 * @param   {String}    guid
 * @return  {object}            实例对象
 */
 window["baiduInstance"] = function(guid) {
     return baidu.$$._instances[guid];
 }

//  2011.11.23  meizz   添加 baiduInstance 这个全局方法，可以快速地通过guid得到实例对象
//  2011.11.22  meizz   废除创建类时指定guid的模式，guid只作为只读属性
//  2011.11.22  meizz   废除 baidu.lang._instances 模块，由统一的global机制完成；




/**
 * 自定义的事件对象。
 * @class
 * @name 	baidu.lang.Event
 * @grammar baidu.lang.Event(type[, target])
 * @param 	{string} type	 事件类型名称。为了方便区分事件和一个普通的方法，事件类型名称必须以"on"(小写)开头。
 * @param 	{Object} [target]触发事件的对象
 * @meta standard
 * @remark 引入该模块，会自动为Class引入3个事件扩展方法：addEventListener、removeEventListener和dispatchEvent。
 * @meta standard
 * @see baidu.lang.Class
 */
baidu.lang.Event = function (type, target) {
    this.type = type;
    this.returnValue = true;
    this.target = target || null;
    this.currentTarget = null;
};
 
/**
 * 派发自定义事件，使得绑定到自定义事件上面的函数都会被执行。引入baidu.lang.Event后，Class的子类实例才会获得该方法。
 * @grammar obj.dispatchEvent(event, options)
 * @param {baidu.lang.Event|String} event 	Event对象，或事件名称(1.1.1起支持)
 * @param {Object} 					options 扩展参数,所含属性键值会扩展到Event对象上(1.2起支持)
 * @remark 处理会调用通过addEventListenr绑定的自定义事件回调函数之外，还会调用直接绑定到对象上面的自定义事件。例如：<br>
myobj.onMyEvent = function(){}<br>
myobj.addEventListener("onMyEvent", function(){});
 */
baidu.lang.Class.prototype.fire =
baidu.lang.Class.prototype.dispatchEvent = function (event, options) {
    baidu.lang.isString(event) && (event = new baidu.lang.Event(event));

    !this.__listeners && (this.__listeners = {});

    // 20100603 添加本方法的第二个参数，将 options extend到event中去传递
    options = options || {};
    for (var i in options) {
        event[i] = options[i];
    }

    var i, n, me = this, t = me.__listeners, p = event.type;
    event.target = event.target || (event.currentTarget = me);

    // 支持非 on 开头的事件名
    p.indexOf("on") && (p = "on" + p);

    typeof me[p] == "function" && me[p].apply(me, arguments);

    if (typeof t[p] == "object") {
        for (i=0, n=t[p].length; i<n; i++) {
            t[p][i] && t[p][i].apply(me, arguments);
        }
    }
    return event.returnValue;
};

/**
 * 注册对象的事件监听器。引入baidu.lang.Event后，Class的子类实例才会获得该方法。
 * @grammar obj.addEventListener(type, handler[, key])
 * @param   {string}   type         自定义事件的名称
 * @param   {Function} handler      自定义事件被触发时应该调用的回调函数
 * @return  {Function}              将用户注入的监听函数返回，以便移除事件监听，特别适用于匿名函数。
 * @remark  事件类型区分大小写。如果自定义事件名称不是以小写"on"开头，该方法会给它加上"on"再进行判断，即"click"和"onclick"会被认为是同一种事件。 
 */
baidu.lang.Class.prototype.on =
baidu.lang.Class.prototype.addEventListener = function (type, handler, key) {
    if (typeof handler != "function") {
        return;
    }

    !this.__listeners && (this.__listeners = {});

    var i, t = this.__listeners;

    type.indexOf("on") && (type = "on" + type);

    typeof t[type] != "object" && (t[type] = []);

    // 避免函数重复注册
    for (i = t[type].length - 1; i >= 0; i--) {
        if (t[type][i] === handler) return handler;
    };

    t[type].push(handler);

    // [TODO delete 2013] 2011.12.19 兼容老版本，2013删除此行
    key && typeof key == "string" && (t[type][key] = handler);

    return handler;
};

//  2011.12.19  meizz   很悲剧，第三个参数 key 还需要支持一段时间，以兼容老版本脚本
//  2011.11.24  meizz   事件添加监听方法 addEventListener 移除第三个参数 key，添加返回值 handler
//  2011.11.23  meizz   事件handler的存储对象由json改成array，以保证注册函数的执行顺序
//  2011.11.22  meizz   将 removeEventListener 方法分拆到 baidu.lang.Class.removeEventListener 中，以节约主程序代码

/**
 * 各种页面的UI组件
 * @namespace baidu.ui
 */
baidu.ui = baidu.ui || { version: '1.3.9' };

/**
 * 通过uiType找到UI类
 * @function
 * @grammar baidu.ui.getUI(uiType)
 * @param  {String} uiType  查找规则：suggestion -> baidu.ui.Suggestion，toolbar-spacer -> baidu.ui.Toolbar.Spacer.
 * @return {object} UI类
 * @author berg
 */
baidu.ui.getUI = function(uiType){
    var uiType = uiType.split('-'),
        result = baidu.ui,
        len = uiType.length,
        i = 0;

    for (; i < len; i++) {
        result = result[uiType[i].charAt(0).toUpperCase() + uiType[i].slice(1)];
    }
    return result;
};



/**
 * 创建一个ui控件
 * @function
 * @grammar baidu.ui.create(UI, options)
 * @param {object|String} UI控件类或者uiType
 * @param {object} options optional 控件的初始化属性
 * @config {Boolean} autoRender 是否自动render，默认true
 * @config {String|HTMLElement} render render到的元素
 * @config {Object} parent 父控件
 * @return {Object} 创建好的控件实例
 * @author berg
 */
baidu.ui.create = function(UI, options){
    if(baidu.lang.isString(UI)){
        UI = baidu.ui.getUI(UI);
    }
    return new UI(options);
};






/**
 * 事件监听器的存储表
 * @private
 * @meta standard
 */
baidu.event._listeners = baidu.event._listeners || [];



/**
 * 为目标元素添加事件监听器
 * @name baidu.event.on
 * @function
 * @grammar baidu.event.on(element, type, listener)
 * @param {HTMLElement|string|window} element 目标元素或目标元素id
 * @param {string} type 事件类型
 * @param {Function} listener 需要添加的监听器
 * @remark
 * 
1. 不支持跨浏览器的鼠标滚轮事件监听器添加<br>
2. 改方法不为监听器灌入事件对象，以防止跨iframe事件挂载的事件对象获取失败
    
 * @shortcut on
 * @meta standard
 * @see baidu.event.un
 * @returns {HTMLElement|window} 目标元素
 */
baidu.event.on = /**@function*/function (element, type, listener) {
    type = type.replace(/^on/i, '');
    element = baidu.dom._g(element);

    var realListener = function (ev) {
            // 1. 这里不支持EventArgument,  原因是跨frame的事件挂载
            // 2. element是为了修正this
            listener.call(element, ev);
        },
        lis = baidu.event._listeners,
        filter = baidu.event._eventFilter,
        afterFilter,
        realType = type;
    type = type.toLowerCase();
    // filter过滤
    if(filter && filter[type]){
        afterFilter = filter[type](element, type, realListener);
        realType = afterFilter.type;
        realListener = afterFilter.listener;
    }
    
    // 事件监听器挂载
    if (element.addEventListener) {
        element.addEventListener(realType, realListener, false);
    } else if (element.attachEvent) {
        element.attachEvent('on' + realType, realListener);
    }
  
    // 将监听器存储到数组中
    lis[lis.length] = [element, type, listener, realListener, realType];
    return element;
};

// 声明快捷方法
baidu.on = baidu.event.on;

/**
 * 为目标元素移除事件监听器
 * @name baidu.event.un
 * @function
 * @grammar baidu.event.un(element, type, listener)
 * @param {HTMLElement|string|window} element 目标元素或目标元素id
 * @param {string} type 事件类型
 * @param {Function} listener 需要移除的监听器
 * @shortcut un
 * @meta standard
 * @see baidu.event.on
 *             
 * @returns {HTMLElement|window} 目标元素
 */
baidu.event.un = function (element, type, listener) {
    element = baidu.dom._g(element);
    type = type.replace(/^on/i, '').toLowerCase();
    
    var lis = baidu.event._listeners, 
        len = lis.length,
        isRemoveAll = !listener,
        item,
        realType, realListener;
    
    //如果将listener的结构改成json
    //可以节省掉这个循环，优化性能
    //但是由于un的使用频率并不高，同时在listener不多的时候
    //遍历数组的性能消耗不会对代码产生影响
    //暂不考虑此优化
    while (len--) {
        item = lis[len];
        
        // listener存在时，移除element的所有以listener监听的type类型事件
        // listener不存在时，移除element的所有type类型事件
        if (item[1] === type
            && item[0] === element
            && (isRemoveAll || item[2] === listener)) {
           	realType = item[4];
           	realListener = item[3];
            if (element.removeEventListener) {
                element.removeEventListener(realType, realListener, false);
            } else if (element.detachEvent) {
                element.detachEvent('on' + realType, realListener);
            }
            lis.splice(len, 1);
        }
    }
    
    return element;
};

// 声明快捷方法
baidu.un = baidu.event.un;



/**
 * UI基类，所有的UI都应该从这个类中派生出去
 * @name baidu.ui.Base
 * @grammar baidu.ui.Base
 * @class
 * @return {baidu.ui.Base}
 * @author berg
 */
baidu.ui.Base = 
/**
 * @lends baidu.ui.Base.prototype
 */
{

    id : "",

    /**
     * 获得当前控件的id
     * @param {string} optional key 
     * @return {string} id
     */
    getId : function(key){
        var ui = this, idPrefix;
        //通过guid区别多实例
        idPrefix = "tangram-" + ui.uiType + '--' + (ui.id ? ui.id : ui.guid);
        return key ? idPrefix + "-" + key : idPrefix;
    },

    /**
     * 获得class，支持skin
     *
     * @param {string} optional key
     *
     * @return {string} className
     */
    getClass : function(key){
        var me = this,
            className = me.classPrefix,
            skinName = me.skin;
         if (key) {
             className += '-' + key;
             skinName += '-' + key;
         }
         if (me.skin) {
             className += ' ' + skinName;
         }
         return className;
    },

    getMain : function(){
        return baidu.g(this.mainId);
    },

    getBody : function(){
        return baidu.g(this.getId());
    },

    
    /**
     * 控件类型：如dialog
     */
    uiType : "",
    
    /**
     * 获取调用的字符串的引用前缀
     */
    getCallRef : function(){
        return "window['$BAIDU$']._instances['" + this.guid + "']";
    },

    /**
     * 获取调用的字符串
     */
    getCallString : function(fn){
        var i = 0,
            arg = Array.prototype.slice.call(arguments, 1),
            len = arg.length;
        for (; i < len; i++) {
            if (typeof arg[i] == 'string') {
                arg[i] = "'" + arg[i] +"'";
            }
        }
        //如果被闭包包起来了，用baidu.lang.instance会找到最外面的baidu函数，可能出错
        return this.getCallRef() 
                + '.' + fn + '('
                + arg.join(',') 
                + ');'; 
    },

    /**
     * 添加事件. 避免析构中漏掉注销事件.
     * @param {HTMLElement|string|window} element 目标元素或目标元素id
     * @param {string} type 事件类型
     * @param {Function} listener 需要添加的监听器
     */
    on : function(element, type, listener){
        baidu.on(element, type, listener);
        this.addEventListener("ondispose", function(){
            baidu.un(element, type, listener);
        });
    },

    /**
     * 渲染控件到指定的元素
     * @param {HTMLElement} main optional   要渲染到的元素，可选。
     *                                      如果不传此参数，则会在body下创建一个绝对定位的div做为main
     * @return  {HTMLElement} main 渲染到的元素
     */
    renderMain : function(main){
        var ui = this,
            i = 0,
            len;
        //如果被渲染过就不重复渲染
        if (ui.getMain()) {
            return ;
        }
        main = baidu.g(main);
        //如果没有main元素，创建一个在body下面的div当作main
        if(!main){
            main = document.createElement('div');
            document.body.appendChild(main);
            main.style.position = "absolute";
            //给这个元素创建一个class，方便用户控制
            main.className = ui.getClass("main");
        }
        if(!main.id){
            main.id = ui.getId("main");
        }
        ui.mainId = main.id;
        main.setAttribute('data-guid', ui.guid);

        return main;
    },

    /**
     * 销毁当前实例
     */
    dispose : function(){
        this.dispatchEvent("dispose");
        baidu.lang.Class.prototype.dispose.call(this);
    }
};



/**
 * 判断目标参数是否为function或Function实例
 * @name baidu.lang.isFunction
 * @function
 * @grammar baidu.lang.isFunction(source)
 * @param {Any} source 目标参数
 * @version 1.2
 * @see baidu.lang.isString,baidu.lang.isObject,baidu.lang.isNumber,baidu.lang.isArray,baidu.lang.isElement,baidu.lang.isBoolean,baidu.lang.isDate
 * @meta standard
 * @returns {boolean} 类型判断结果
 */
baidu.lang.isFunction = function (source) {
    // chrome下,'function' == typeof /a/ 为true.
    return '[object Function]' == Object.prototype.toString.call(source);
};


/**
 * 创建一个UI控件类
 * @function
 * @grammar baidu.ui.createUI(constructor, options)
 * @param {Function} constructor ui控件构造器
 * @param {Object} options 选项
 * @return {Object} ui控件
 */
baidu.ui.createUI = function(constructor, options) {
    options = options || {};
    var superClass = options.superClass || baidu.lang.Class,
        lastInherit = superClass == baidu.lang.Class ? 1 : 0,
        i,
        n,
        ui = function(opt, _isInherits){// 创建新类的真构造器函数
            var me = this;
            opt = opt || {};
            // 继承父类的构造器，将isInherits设置成true，在后面不执行render操作
            superClass.call(me, !lastInherit ? opt : (opt.guid || ""), true);

            //扩展静态配置到this上
            baidu.object.extend(me, ui.options);
            //扩展当前options中的项到this上
            baidu.object.extend(me, opt);
            //baidu.object.merge(me, opt, {overwrite:true, recursive:true});

            me.classPrefix = me.classPrefix || "tangram-" + me.uiType.toLowerCase();

            //初始化行为
            //行为就是在控件实例上附加一些属性和方法
            for(i in baidu.ui.behavior){
                //添加行为到控件上
                if(typeof me[i] != 'undefined' && me[i]){
                    baidu.object.extend(me, baidu.ui.behavior[i]);
                    if(baidu.lang.isFunction(me[i])){
                        me.addEventListener("onload", function(){
                            baidu.ui.behavior[i].call(me[i].apply(me));
                        });
                    }else{
                        baidu.ui.behavior[i].call(me);
                    }
                }
            }

            //执行控件自己的构造器
            constructor.apply(me, arguments);

            //执行插件的构造器
            for (i=0, n=ui._addons.length; i<n; i++) {
                ui._addons[i](me);
            }
            if(opt.parent && me.setParent){
                me.setParent(opt.parent);
            }
            if(!_isInherits && opt.autoRender){ 
                me.render(opt.element);
            }
        },
        C = function(){};

    C.prototype = superClass.prototype;

    //继承父类的原型链
    var proto = ui.prototype = new C();

    //继承Base中的方法到prototype中
    for (i in baidu.ui.Base) {
        proto[i] = baidu.ui.Base[i];
    }

    /**
     * 扩展控件的prototype
     * 
     * @param {Object} json 要扩展进prototype的对象
     *
     * @return {Object} 扩展后的对象
     */
    ui.extend = function(json){
        for (i in json) {
            ui.prototype[i] = json[i];
        }
        return ui;  // 这个静态方法也返回类对象本身
    };

    //插件支持
    ui._addons = [];
    ui.register = function(f){
        if (typeof f == "function")
            ui._addons.push(f);
    };
    
    //静态配置支持
    ui.options = {};
    
    return ui;
};



/**
 * 所有类的实例的容器
 * key为每个实例的guid
 * @meta standard
 */

window[baidu.guid]._instances = window[baidu.guid]._instances || {};

//	[TODO]	meizz	在2012年版本中将删除此模块

/**
 * 根据参数(guid)的指定，返回对应的实例对象引用
 * @name baidu.lang.instance
 * @function
 * @grammar baidu.lang.instance(guid)
 * @param {string} guid 需要获取实例的guid
 * @meta standard
 *             
 * @returns {Object|null} 如果存在的话，返回;否则返回null。
 */
baidu.lang.instance = function (guid) {
    return window[baidu.guid]._instances[guid] || null;
};

/**
 * 提供给setAttr与getAttr方法作名称转换使用
 * ie6,7下class要转换成className
 * @meta standard
 */

baidu.dom._NAME_ATTRS = (function () {
    var result = {
        'cellpadding': 'cellPadding',
        'cellspacing': 'cellSpacing',
        'colspan': 'colSpan',
        'rowspan': 'rowSpan',
        'valign': 'vAlign',
        'usemap': 'useMap',
        'frameborder': 'frameBorder'
    };
    
    if (baidu.browser.ie < 8) {
        result['for'] = 'htmlFor';
        result['class'] = 'className';
    } else {
        result['htmlFor'] = 'for';
        result['className'] = 'class';
    }
    
    return result;
})();


/**
 * 获取目标元素的属性值
 * @name baidu.dom.getAttr
 * @function
 * @grammar baidu.dom.getAttr(element, key)
 * @param {HTMLElement|string} element 目标元素或目标元素的id
 * @param {string} key 要获取的attribute键名
 * @shortcut getAttr
 * @meta standard
 * @see baidu.dom.setAttr,baidu.dom.setAttrs
 *             
 * @returns {string|null} 目标元素的attribute值，获取不到时返回null
 */
baidu.dom.getAttr = function (element, key) {
    element = baidu.dom.g(element);

    if ('style' == key){
        return element.style.cssText;
    }

    key = baidu.dom._NAME_ATTRS[key] || key;
    return element.getAttribute(key);
};

// 声明快捷方法
baidu.getAttr = baidu.dom.getAttr;

//

/**
 * 获取元素所在的控件
 * @function
 * @grammar baidu.ui.get(element)
 * @param {HTMLElement|string} 要查找的元素，如果是字符串，则查找这个guid为此字符串的控件
 * @param {string} optional  type 匹配查找指定类型的控件【暂未支持】
 * @return {object} ui控件
 */
baidu.ui.get = function(element/*, type*/){
    var buid;

    //如果是string，则按照guid来找
    if(baidu.lang.isString(element)){
        return baidu.lang.instance(element);
    }else{
        /*
         *type = type.toLowerCase();
         */
        do{
            //如果元素是document
        	//加上了!element判断,防止游离节点的父节点为null的情况  rocy@2010-08-05
            if(!element || element.nodeType == 9){
                return null;
            }
            if(buid = baidu.dom.getAttr(element, "data-guid")){
                     return baidu.lang.instance(buid);
                /*
                 *if( !type || buid.toLowerCase().indexOf(type) === 0){
                 *    return baidu.lang.instance(buid);
                 *}
                 */
            }
        }while((element = element.parentNode) != document.body)
    }
};


/**
 * Suggestion基类，建立一个Suggestion实例
 * @class
 * @grammar new baidu.ui.Suggestion(options)
 * @param  {Object}   [options]        选项.
 * @config {Function} onshow           当显示时触发。
 * @config {Function} onhide           当隐藏时触发，input或者整个window失去焦点，或者confirm以后会自动隐藏。
 * @config {Function} onconfirm        当确认条目时触发，回车后，或者在条目上按鼠标会触发确认操作。参数是event对象，其中有data属性，包括item和index值。item为当前确认的条目，index是条目索引。。
 * @config {Function} onbeforepick     使用方向键选中某一行，鼠标点击前触发。
 * @config {Function} onpick           使用方向键选中某一行，鼠标点击时触发。参数是event对象，其中有data属性，包括item和index值。item为当前确认的条目，index是条目索引。
 * @config {Function} onhighlight      当高亮时触发，使用方向键移过某一行，使用鼠标滑过某一行时会触发高亮。参数是event对象，其中有data属性，包括item和index值。item为当前确认的条目，index是条目索引。
 * @config {Function} onload
 * @config {Function} onmouseoveritem
 * @config {Function} onmouseoutitem
 * @config {Function} onmousedownitem
 * @config {Function} onitemclick
 * @config {Function} view             重新定位时，会调用这个方法来获取新的位置，传入的参数中会包括top、 left、width三个值。
 * @config {Function} getData          在需要获取数据的时候会调用此函数来获取数据，传入的参数word是用户在input中输入的数据。
 * @config {String}   prependHTML      写在下拉框列表前面的html
 * @config {String}   appendHTML       写在下拉框列表后面的html
 * @config {Boolean}  holdHighLight    鼠标移出待选项区域后，是否保持高亮元素的状态
 * @plugin coverable  支持背景遮罩
 * @plugin data		  提供数据内存缓存
 * @plugin fixWidth	  提供位置校准功能
 * @plugin input	  支持快捷键操作
 */
baidu.ui.Suggestion = baidu.ui.createUI(function(options) {
    var me = this;

    me.addEventListener('onload', function() {
        //监听suggestion外面的鼠标点击
        me.on(document, 'mousedown', me.documentMousedownHandler);

        //窗口失去焦点就hide
        me.on(window, 'blur', me.windowBlurHandler);
    });

    //初始化dom事件函数
    me.documentMousedownHandler = me._getDocumentMousedownHandler();
    me.windowBlurHandler = me._getWindowBlurHandler();

    //value为在data中的value
    me.enableIndex = [];
    //这个index指的是当前高亮条目在enableIndex中的index而非真正在data中的index
    me.currentIndex = -1;

}).extend(
/**
 *  @lends baidu.ui.Suggestion.prototype
 */
{
    uiType: 'suggestion',
    onbeforepick: new Function,
    onpick: new Function,
    onconfirm: new Function,
    onhighlight: new Function,
    onshow: new Function,
    onhide: new Function,

    /**
     * @private
     */
    getData: function() {return []},
    prependHTML: '',
    appendHTML: '',

    currentData: {},

    tplDOM: "<div id='#{0}' class='#{1}' style='position:relative; top:0px; left:0px'></div>",
    tplPrependAppend: "<div id='#{0}' class='#{1}'>#{2}</div>",
    tplBody: '<table cellspacing="0" cellpadding="2"><tbody>#{0}</tbody></table>',
    tplRow: '<tr><td id="#{0}" onmouseover="#{2}" onmouseout="#{3}" onmousedown="#{4}" onclick="#{5}" class="#{6}">#{1}</td></tr>',

    /**
     * 获得suggestion的外框HTML string
     * @private
     * @return {String}
     */
    getString: function() {
        var me = this;
        return baidu.format(
            me.tplDOM,
            me.getId(),
            me.getClass(),
            me.guid
        );
    },

    /**
     * 将suggestion渲染到dom树中
     * @public
     * @param {HTMLElement} target
     * @return {Null}
     */
    render: function(target) {
        var me = this,
            main,
            target = baidu.g(target);

        if (me.getMain() || !target) {
            return;
        }
        if (target.id) {
            me.targetId = target.id;
        }else {
            me.targetId = target.id = me.getId('input');
        }

        main = me.renderMain();

        main.style.display = 'none';
        main.innerHTML = me.getString();

        this.dispatchEvent('onload');
    },

    /**
     * 当前suggestion是否处于显示状态
     * @private
     * @return {Boolean}
     */
    _isShowing: function() {
        var me = this,
            main = me.getMain();
        return main && main.style.display != 'none';
    },

    /**
     * 把某个词放到input框中
     * @public
     * @param {String} index 条目索引.
     * @return {Null}
     */
    pick: function(index) {
        var me = this,
            currentData = me.currentData,
            word = currentData && typeof index == 'number' && typeof currentData[index] != 'undefined' ? currentData[index].value : index,
            eventData = {
                data: {
                    item: word == index ? {value: index, content: index} : currentData[index],
                    index: index
                }
            };

        if (me.dispatchEvent('onbeforepick', eventData)) {
            me.dispatchEvent('onpick', eventData);
        }
    },

    /**
     * 绘制suggestion
     * @public
     * @param {String}  word 触发sug的字符串.
     * @param {Object}  data suggestion数据.
     * @param {Boolean} [showEmpty] 如果sug数据为空是否依然显示 默认为false.
     * @return {Null}
     */
    show: function(word, data, showEmpty) {
        var i = 0,
            len = data.length,
            me = this;

        me.enableIndex = [];
        me.currentIndex = -1;

        if (len == 0 && !showEmpty) {
            me.hide();
        } else {
            me.currentData = [];
            for (; i < len; i++) {
                if (typeof data[i].value != 'undefined') {
                    me.currentData.push(data[i]);
                }else {
                    me.currentData.push({
                        value: data[i],
                        content: data[i]
                    });
                }
                if (typeof data[i]['disable'] == 'undefined' || data[i]['disable'] == false) {
                    me.enableIndex.push(i);
                }
            }

            me.getBody().innerHTML = me._getBodyString();
            me.getMain().style.display = 'block';
            me.dispatchEvent('onshow');
        }
    },

    /**
     * 隐藏suggestion
     * @public
     * @return {Null}
     */
    hide: function() {
        var me = this;

        //如果已经是隐藏状态就不用派发后面的事件了
        if (!me._isShowing())
            return;
        
        //如果当前有选中的条目，将其放到input中
        if(me.currentIndex >= 0 && me.holdHighLight){
            var currentData = me.currentData,
                j = -1;
            for(var i=0, len=currentData.length; i<len; i++){
                if(typeof currentData[i].disable == 'undefined' || currentData[i].disable == false){
                    j++;
                    if(j == me.currentIndex)
                        me.pick(i);
                }
            }
        }
        
        me.getMain().style.display = 'none';
        me.dispatchEvent('onhide');
    },

    /**
     * 高亮某个条目
     * @public
     * @param {String} index 条目索引.
     * @return {Null}
     */
    highLight: function(index) {
        var me = this,
            enableIndex = me.enableIndex,
            item = null;

        //若需要高亮的item被设置了disable，则直接返回
        if (!me._isEnable(index)) return;

        me.currentIndex >= 0 && me._clearHighLight();
        item = me._getItem(index);
        baidu.addClass(item, me.getClass('current'));
        me.currentIndex = baidu.array.indexOf(enableIndex, index);

        me.dispatchEvent('onhighlight', {
            index: index,
            data: me.getDataByIndex(index)
        });
    },

    /**
     * 清除item高亮状态
     * @public
     * @return {Null}
     */
    clearHighLight: function() {
        var me = this,
            currentIndex = me.currentIndex,
            index = me.enableIndex[currentIndex];

        //若当前没有元素处于高亮状态，则不发出事件
        me._clearHighLight() && me.dispatchEvent('onclearhighlight', {
            index: index,
            data: me.getDataByIndex(index)
        });
    },

    /**
     * 清除suggestion中tr的背景样式
     * @private
     * @return {Boolean} bool 当前有item处于高亮状态并成功进行clear highlight,返回true，否则返回false.
     */
    _clearHighLight: function() {
        var me = this,
            currentIndex = me.currentIndex,
            enableIndex = me.enableIndex,
            item = null;

        if (currentIndex >= 0) {
            item = me._getItem(enableIndex[currentIndex]);
            baidu.removeClass(item, me.getClass('current'));
            me.currentIndex = -1;
            return true;
        }
        return false;
    },

    /**
     * confirm指定的条目
     * @public
     * @param {Number|String} index or item.
     * @param {String} source 事件来源.
     * @return {Null}
     */
    confirm: function(index, source) {
        var me = this;

        if (source != 'keyboard') {
            if (!me._isEnable(index)) return;
        }

        me.pick(index);
        me.dispatchEvent('onconfirm', {
            data: me.getDataByIndex(index) || index,
            source: source
        });
        me.currentIndex = -1;
        me.hide();
    },

    /**
     * 根据index拿到传给event的data数据
     * @private
     * @return {Object}
     * @config {HTMLElement} item
     * @config {Number} index
     */
    getDataByIndex: function(index) {

        return {
            item: this.currentData[index],
            index: index
        };
    },

    /**
     * 获得target的值
     * @public
     * @return {String}
     */
    getTargetValue: function() {
        return this.getTarget().value;
    },

    /**
     * 获得input框元素
     * @public
     * @return {HTMLElement}
     */
    getTarget: function() {
        return baidu.g(this.targetId);
    },

    /**
     * 获得指定的条目
     * @private
     * @return {HTMLElement}
     */
    _getItem: function(index) {
        return baidu.g(this.getId('item' + index));
    },

    /**
     * 渲染body部分的string
     * @private
     * @return {String}
     */
    _getBodyString: function() {

        var me = this,
            html = '',
            itemsHTML = [],
            data = me.currentData,
            len = data.length,
            i = 0;

        function getPrependAppend(name) {
            return baidu.format(
                me.tplPrependAppend,
                me.getId(name),
                me.getClass(name),
                me[name + 'HTML']
            );
        }


        html += getPrependAppend('prepend');

        for (; i < len; i++) {
            itemsHTML.push(baidu.format(
                me.tplRow,
                me.getId('item' + i),
                data[i].content,
                me.getCallRef() + '._itemOver(event, ' + i + ')',
                me.getCallRef() + '._itemOut(event, ' + i + ')',
                me.getCallRef() + '._itemDown(event, ' + i + ')',
                me.getCallRef() + '._itemClick(event, ' + i + ')',
                (typeof data[i]['disable'] == 'undefined' || data[i]['disable'] == false) ? '' : me.getClass('disable')
            ));
        }

        html += baidu.format(
            me.tplBody, 
            itemsHTML.join('')
        );
        html += getPrependAppend('append');
        return html;
    },

    /**
     * 当焦点通过鼠标或键盘移动到某个条目
     * @private
     * @param {Event} e
     * @param {Number} index
     * @return {Null}
     */
    _itemOver: function(e, index) {
        var me = this;
        baidu.event.stop(e || window.event);
        me._isEnable(index) && me.highLight(index);

        me.dispatchEvent('onmouseoveritem', {
            index: index,
            data: me.getDataByIndex(index)
        });
    },

    /**
     * 当焦点通过鼠标或键盘移出某个条目
     * @private
     * @param {Event} e
     * @param {Number} index
     * @return {Null}
     */
    _itemOut: function(e, index) {
        var me = this;
        baidu.event.stop(e || window.event);
        if(!me.holdHighLight)
            me._isEnable(index) && me.clearHighLight();

        me.dispatchEvent('onmouseoutitem', {
            index: index,
            data: me.getDataByIndex(index)
        });
    },

    /**
     * 当通过鼠标选中某个条目
     * @private
     * @param {Event} e
     * @param {Number} index
     * @return {Null}
     */
    _itemDown: function(e, index) {
        var me = this;
        baidu.event.stop(e || window.event);

        me.dispatchEvent('onmousedownitem', {
            index: index,
            data: me.getDataByIndex(index)
        });
    },

    /**
     * 当鼠标点击某个条目
     * @private
     * @param {Event} e
     * @param {Number} index
     * @return {Null}
     */
    _itemClick: function(e, index) {
        var me = this;
        baidu.event.stop(e || window.event);

        me.dispatchEvent('onitemclick', {
            index: index,
            data: me.getDataByIndex(index)
        });

        me._isEnable(index) && me.confirm(index, 'mouse');
    },

    /**
     * 判断item是否处于enable状态
     * @param {Number} index 索引，和传入的data中相同.
     * @return {Boolean}
     */
    _isEnable: function(index) {
        var me = this;
        return baidu.array.contains(me.enableIndex, index);
    },

    /**
     * 外部事件绑定
     * @private
     * @return {Function}
     */
    _getDocumentMousedownHandler: function() {
        var me = this;
        return function(e) {
            // todo : baidu.event.getTarget();
            e = e || window.event;
            var element = e.target || e.srcElement,
                ui = baidu.ui.get(element);
            //如果在target上面或者me内部
            if (element == me.getTarget() || (ui && ui.uiType == me.uiType)) {
                return;
            }
            me.hide();
        };
    },

    /**
     * 外部事件绑定
     * @private
     * @return {Function}
     */
    _getWindowBlurHandler: function() {
        var me = this;
        return function() {
            me.hide();
        };
    },

    /**
     * 销毁suggesiton
     * @public
     * @return {Null}
     */
    dispose: function() {
        var me = this;
        me.dispatchEvent('dispose');

        baidu.dom.remove(me.mainId);

        baidu.lang.Class.prototype.dispose.call(this);
    }
});

/**
 * Tangram UI
 * Copyright 2009 Baidu Inc. All rights reserved.
 */


/**
 * @namespace baidu.ui.behavior 为各个控件增加装饰器
 */
baidu.ui.behavior = baidu.ui.behavior || {};

/**
 * 获取目标元素的直接子元素列表
 * @name baidu.dom.children
 * @function
 * @grammar baidu.dom.children(element)
 * @param {HTMLElement|String} element 目标元素或目标元素的id
 * @meta standard
 *             
 * @returns {Array} 目标元素的子元素列表，没有子元素时返回空数组
 */
baidu.dom.children = function (element) {
    element = baidu.dom.g(element);

    for (var children = [], tmpEl = element.firstChild; tmpEl; tmpEl = tmpEl.nextSibling) {
        if (tmpEl.nodeType == 1) {
            children.push(tmpEl);
        }
    }
    
    return children;    
};

/**
 * 将目标元素添加到基准元素之前
 * @name baidu.dom.insertBefore
 * @function
 * @grammar baidu.dom.insertBefore(newElement, existElement)
 * @param {HTMLElement|string} newElement 被添加的目标元素
 * @param {HTMLElement|string} existElement 基准元素
 * @meta standard
 * @see baidu.dom.insertAfter
 *             
 * @returns {HTMLElement} 被添加的目标元素
 */
baidu.dom.insertBefore = function (newElement, existElement) {
    var g, existParent;
    g = baidu.dom._g;
    newElement = g(newElement);
    existElement = g(existElement);
    existParent = existElement.parentNode;

    if (existParent) {
        existParent.insertBefore(newElement, existElement);
    }

    return newElement;
};

/**
 * 设置目标元素的style样式值
 * @name baidu.dom.setStyle
 * @function
 * @grammar baidu.dom.setStyle(element, key, value)
 * @param {HTMLElement|string} element 目标元素或目标元素的id
 * @param {string} key 要设置的样式名
 * @param {string} value 要设置的样式值
 * @remark
 * 
            为了精简代码，本模块默认不对任何浏览器返回值进行归一化处理（如使用getStyle时，不同浏览器下可能返回rgb颜色或hex颜色），也不会修复浏览器的bug和差异性（如设置IE的float属性叫styleFloat，firefox则是cssFloat）。<br />
baidu.dom._styleFixer和baidu.dom._styleFilter可以为本模块提供支持。<br />
其中_styleFilter能对颜色和px进行归一化处理，_styleFixer能对display，float，opacity，textOverflow的浏览器兼容性bug进行处理。
		
 * @shortcut setStyle
 * @meta standard
 * @see baidu.dom.getStyle,baidu.dom.setStyles
 *             
 * @returns {HTMLElement} 目标元素
 */
baidu.dom.setStyle = function (element, key, value) {
    var dom = baidu.dom, fixer;
    
    // 放弃了对firefox 0.9的opacity的支持
    element = dom.g(element);
    key = baidu.string.toCamelCase(key);

    if (fixer = dom._styleFilter) {
        value = fixer.filter(key, value, 'set');
    }

    fixer = dom._styleFixer[key];
    (fixer && fixer.set) ? fixer.set(element, value, key) : (element.style[fixer || key] = value);

    return element;
};

// 声明快捷方法
baidu.setStyle = baidu.dom.setStyle;

/**
 * 批量设置目标元素的style样式值
 * @name baidu.dom.setStyles
 * @function
 * @grammar baidu.dom.setStyles(element, styles)
 * @param {HTMLElement|string} element 目标元素或目标元素的id
 * @param {Object} styles 要设置的样式集合
 * @shortcut setStyles
 * @meta standard
 * @see baidu.dom.setStyle,baidu.dom.getStyle
 *             
 * @returns {HTMLElement} 目标元素
 */
baidu.dom.setStyles = function (element, styles) {
    element = baidu.dom.g(element);

    for (var key in styles) {
        baidu.dom.setStyle(element, key, styles[key]);
    }

    return element;
};

// 声明快捷方法
baidu.setStyles = baidu.dom.setStyles;

/**
 * 提供给setStyle与getStyle使用
 * @meta standard
 */
baidu.dom._styleFilter[baidu.dom._styleFilter.length] = {
    set: function (key, value) {
        if (value.constructor == Number 
            && !/zIndex|fontWeight|opacity|zoom|lineHeight/i.test(key)){
            value = value + "px";
        }

        return value;
    }
};





/**
 * 按照border-box模型设置元素的height和width值。只支持元素的padding/border/height/width使用同一种计量单位的情况。<br/> 不支持：<br/> 1. 非数字值(medium)<br/> 2. em/px在不同的属性中混用
 * @name baidu.dom.setBorderBoxSize
 * @author berg
 * @function
 * @grammar baidu.dom.setBorderBoxSize(element, size)
 * @param {HTMLElement|string} element 元素或DOM元素的id
 * @param {object} size 包含height和width键名的对象
 *
 * @see baidu.dom.setBorderBoxWidth, baidu.dom.setBorderBoxHeight
 *
 * @return {HTMLElement}  设置好的元素
 */
baidu.dom.setBorderBoxSize = /**@function*/function (element, size) {
    var result = {};
    size.width && (result.width = parseFloat(size.width));
    size.height && (result.height = parseFloat(size.height));

    function getNumericalStyle(element, name){
        return parseFloat(baidu.getStyle(element, name)) || 0;
    }
    
    if(baidu.browser.isStrict){
        if(size.width){
            result.width = parseFloat(size.width)  -
                           getNumericalStyle(element, 'paddingLeft') - 
                           getNumericalStyle(element, 'paddingRight') - 
                           getNumericalStyle(element, 'borderLeftWidth') -
                           getNumericalStyle(element, 'borderRightWidth');
            result.width < 0 && (result.width = 0);
        }
        if(size.height){
            result.height = parseFloat(size.height) -
                            getNumericalStyle(element, 'paddingTop') - 
                            getNumericalStyle(element, 'paddingBottom') - 
                            getNumericalStyle(element, 'borderTopWidth') - 
                            getNumericalStyle(element, 'borderBottomWidth');
            result.height < 0 && (result.height = 0);
        }
    }
    return baidu.dom.setStyles(element, result);
};


(function(){
    var Coverable = baidu.ui.behavior.coverable = function() {};
    
    Coverable.Coverable_isShowing = false;
    Coverable.Coverable_iframe;
    Coverable.Coverable_container;
    Coverable.Coverable_iframeContainer;

    /**
     * 显示遮罩，当遮罩不存在时创建遮罩
     * @public
     * @return {NULL}
     */
    Coverable.Coverable_show = function(){
        var me = this;
        if(me.Coverable_iframe){
            me.Coverable_update();
            baidu.setStyle(me.Coverable_iframe, 'display', 'block'); 
            return;
        }
        
        var opt = me.coverableOptions || {},
            container = me.Coverable_container = opt.container || me.getMain(),
            opacity = opt.opacity || '0',
            color = opt.color || '',
            iframe = me.Coverable_iframe = document.createElement('iframe'),
            iframeContainer = me.Coverable_iframeContainer = document.createElement('div');

        //append iframe container
        baidu.dom.children(container).length > 0 ?
            baidu.dom.insertBefore(iframeContainer, container.firstChild):
            container.appendChild(iframeContainer);
       
        //setup iframeContainer styles
        baidu.setStyles(iframeContainer, {
            position: 'absolute',
            top: '0px',
            left: '0px'
        });
        baidu.dom.setBorderBoxSize(iframeContainer,{
            width: container.offsetWidth,
            height: container.offsetHeight
        });

        baidu.dom.setBorderBoxSize(iframe,{
            width: iframeContainer.offsetWidth
        });

        baidu.dom.setStyles(iframe,{
            zIndex  : -1,
            display  : "block",
            border: 0,
            backgroundColor: color,
            filter : 'progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=' + opacity + ')'
        });
        iframeContainer.appendChild(iframe);
        
        iframe.src = "javascript:void(0)";
        iframe.frameBorder = '0';
        iframe.scrolling = 'no';
        iframe.height = '97%';
        me.Coverable_isShowing = true;
    };

    /**
     * 隐藏遮罩
     * @public
     * @return {NULL}
     */
    Coverable.Coverable_hide = function(){
        var me = this,
            iframe = me.Coverable_iframe;
        
        if(!me.Coverable_isShowing){
            return;
        }
        
        baidu.setStyle(iframe, 'display', 'none');
        me.Coverable_isShowing = false;
    };

    /**
     * 更新遮罩
     * @public
     * @param {Object} options
     * @config {Number|String} opacity 透明度
     * @config {String} backgroundColor 背景色
     */
    Coverable.Coverable_update = function(options){
        var me = this,
            options = options || {},
            container = me.Coverable_container,
            iframeContainer = me.Coverable_iframeContainer,
            iframe = me.Coverable_iframe;
  
        baidu.dom.setBorderBoxSize(iframeContainer,{
            width: container.offsetWidth,
            height: container.offsetHeight
        });

        baidu.dom.setBorderBoxSize(iframe,baidu.extend({
            width: baidu.getStyle(iframeContainer, 'width')
        },options));
    };
})();

/**
 * 支持背景遮罩掩盖select、flash、iframe元素
 * @name baidu.ui.Suggestion.Suggestion$coverable
 * @addon baidu.ui.Suggestion
 */
baidu.extend(baidu.ui.Suggestion.prototype, {
    coverable: true,
    coverableOptions: {}
});

baidu.ui.Suggestion.register(function(me) {

    if (me.coverable) {

        me.addEventListener('onshow', function() {
            me.Coverable_show();
        });

        me.addEventListener('onhide', function() {
            me.Coverable_hide();
        });
    }
});

/**
 * 为Suggestion提供数据内存缓存，可对其扩展做本地缓存
 * @name baidu.ui.Suggestion.Suggestion$data
 * @addon baidu.ui.Suggestion
 * @author berg
 */

baidu.ui.Suggestion.extend({
    /**
     * 设置一组数据给suggestion，调用者可以选择是否立即显示这组数据: noShow
	 * @name baidu.ui.Suggestion.Suggestion$data
	 * @addon baidu.ui.Suggestion
	 * @function
	 * @param  {String}  word     关键字
	 * @param  {Array}   data     数据数组，例如["aaa","bbb"]
	 * @param  {Boolean} noShow  为true则不立即显示这组数据
     * @return {null}
     */
    setData: function(word, data, noShow) {
        var me = this;
		me.dataCache[word] = data;
        if (!noShow) {
            me.show(word, me.dataCache[word]);
        }
    }
});

baidu.ui.Suggestion.register(function(me) {
    //初始化dataCache
    me.dataCache = {},
    /*
     * 获取一个词对应的me数据
     * 通过事件返回结果
     */
    me.addEventListener('onneeddata', function(ev, word) {
        var dataCache = me.dataCache;
        if (typeof dataCache[word] == 'undefined') {
            //没有数据就去取数据
            me.getData(word);
        }else {
            //有数据就直接显示
            me.show(word, dataCache[word]);
        }
    });
});

/**
 * 按照border-box模型设置元素的width值
 * 
 * @author berg
 * @name baidu.dom.setBorderBoxWidth
 * @function
 * @grammar baidu.dom.setBorderBoxWidth(element, width)
 * 
 * @param {HTMLElement|string} 	element DOM元素或元素的id
 * @param {number|string} 		width 	要设置的width
 *
 * @return {HTMLElement}  设置好的元素
 * @see baidu.dom.setBorderBoxHeight, baidu.dom.setBorderBoxSize
 * @shortcut dom.setOuterWidth
 */
baidu.dom.setOuterWidth = 
baidu.dom.setBorderBoxWidth = function (element, width) {
    return baidu.dom.setBorderBoxSize(element, {width : width});
};



/**
 * Tangram UI
 * Copyright 2009 Baidu Inc. All rights reserved.
 */




/**
 * 获取目标元素所属的window对象
 * @name baidu.dom.getWindow
 * @function
 * @grammar baidu.dom.getWindow(element)
 * @param {HTMLElement|string} element 目标元素或目标元素的id
 * @see baidu.dom.getDocument
 *             
 * @returns {window} 目标元素所属的window对象
 */
baidu.dom.getWindow = function (element) {
    element = baidu.dom.g(element);
    var doc = baidu.dom.getDocument(element);
    
    // 没有考虑版本低于safari2的情况
    // @see goog/dom/dom.js#goog.dom.DomHelper.prototype.getWindow
    return doc.parentWindow || doc.defaultView || null;
};




/**
 * 设置目标元素的top和left值到用户指定的位置
 * 
 * @name baidu.dom.setPosition
 * @function
 * @grammar baidu.dom.setPosition(element, position)
 * 
 * @param {HTMLElement|string}	element 	目标元素或目标元素的id
 * @param {object} 				position 	位置对象 {top: {number}, left : {number}}
 *
 * @return {HTMLElement}  进行设置的元素
 */
baidu.dom.setPosition = function (element, position) {
    return baidu.dom.setStyles(element, {
        left : position.left - (parseFloat(baidu.dom.getStyle(element, "margin-left")) || 0),
        top : position.top - (parseFloat(baidu.dom.getStyle(element, "margin-top")) || 0)
    });
};





/**
 * 对页面层面的封装，包括页面的高宽属性、以及外部css和js的动态添加
 * @namespace baidu.page
 */
baidu.page = baidu.page || {};


/**
 * 获取页面视觉区域宽度
 * @name baidu.page.getViewWidth
 * @function
 * @grammar baidu.page.getViewWidth()
 * @see baidu.page.getViewHeight
 *             
 * @returns {number} 页面视觉区域宽度
 */
baidu.page.getViewWidth = function () {
    var doc = document,
        client = doc.compatMode == 'BackCompat' ? doc.body : doc.documentElement;

    return client.clientWidth;
};

/**
 * 获取页面视觉区域高度
 * @name baidu.page.getViewHeight
 * @function
 * @grammar baidu.page.getViewHeight()
 * @see baidu.page.getViewWidth
 * @meta standard
 * @returns {number} 页面视觉区域高度
 */
baidu.page.getViewHeight = function () {
    var doc = document,
        client = doc.compatMode == 'BackCompat' ? doc.body : doc.documentElement;

    return client.clientHeight;
};

/**
 * 获取纵向滚动量
 * @name baidu.page.getScrollTop
 * @function
 * @grammar baidu.page.getScrollTop()
 * @see baidu.page.getScrollLeft
 * @meta standard
 * @returns {number} 纵向滚动量
 */
baidu.page.getScrollTop = function () {
    var d = document;
    return window.pageYOffset || d.documentElement.scrollTop || d.body.scrollTop;
};

/**
 * 获取横向滚动量
 * @name baidu.page.getScrollLeft
 * @function
 * @grammar baidu.page.getScrollLeft()
 * @see baidu.page.getScrollTop
 *             
 * @returns {number} 横向滚动量
 */
/**
 * 获取横向滚动量
 * 
 * @return {number} 横向滚动量
 */
baidu.page.getScrollLeft = function () {
    var d = document;
    return window.pageXOffset || d.documentElement.scrollLeft || d.body.scrollLeft;
};





/**
 * 对方法的操作，解决内存泄露问题
 * @namespace baidu.fn
 */
baidu.fn = baidu.fn || {};




/** 
 * 为对象绑定方法和作用域
 * @name baidu.fn.bind
 * @function
 * @grammar baidu.fn.bind(handler[, obj, args])
 * @param {Function|String} handler 要绑定的函数，或者一个在作用域下可用的函数名
 * @param {Object} obj 执行运行时this，如果不传入则运行时this为函数本身
 * @param {args* 0..n} args 函数执行时附加到执行时函数前面的参数
 * @version 1.3
 *
 * @returns {Function} 封装后的函数
 */
baidu.fn.bind = function(func, scope) {
    var xargs = arguments.length > 2 ? [].slice.call(arguments, 2) : null;
    return function () {
        var fn = baidu.lang.isString(func) ? scope[func] : func,
            args = (xargs) ? xargs.concat([].slice.call(arguments, 0)) : arguments;
        return fn.apply(scope || fn, args);
    };
};


/**
 * @author berg, lxp
 * @behavior 为ui控件添加定位行为
 *
 * 根据用户参数将元素定位到指定位置
 * TODO: 1. 用surround做触边折返场景时, 折返的大小通常是原始高宽+另一元素的高宽
 *
 * });
 */
(function() {
    var Posable = baidu.ui.behavior.posable = function() { };

    /**
     * 将控件或者指定元素的左上角放置到指定的坐标
     * @param {Array|Object} coordinate 定位坐标,相对文档左上角的坐标，可以是{x:200,y:300}格式，也可以是[200, 300]格式.
     * @param {HTMLElement|string} element optional 目标元素或目标元素的id，如果不指定，默认为当前控件的主元素.
     * @param {Object} options optional 选项，包括：position/coordinate/offset/insideScreen.
     */
    Posable.setPosition = function(coordinate, element, options) {
        element = baidu.g(element) || this.getMain();
        options = options || {};
        var me = this,
            args = [element, coordinate, options];
        me.__execPosFn(element, '_positionByCoordinate', options.once, args);
    };

    /**
     * 将元素放置到指定的坐标点
     *
     * @param {HTMLElement|string} source 要定位的元素.
     * @param {Array|Object} coordinate 定位坐标,相对文档左上角的坐标，可以是{x:200,y:300}格式，也可以是[200, 300]格式.
     * @param {Object} options optional 选项，同setPosition.
     */
    Posable._positionByCoordinate = function(source, coordinate, options, _scrollJustify) {
        coordinate = coordinate || [0, 0];
        options = options || {};
        
        var me = this,
            elementStyle = {},
            cH = baidu.page.getViewHeight(),
            cW = baidu.page.getViewWidth(),
            scrollLeft = baidu.page.getScrollLeft(),
            scrollTop  = baidu.page.getScrollTop(),
            sourceWidth = source.offsetWidth,
            sourceHeight = source.offsetHeight,
            offsetParent = source.offsetParent,
            parentPos = (!offsetParent || offsetParent == document.body) ? {left: 0, top: 0} : baidu.dom.getPosition(offsetParent);

        //兼容position大小写
        options.position = (typeof options.position !== 'undefined') ? options.position.toLowerCase() : 'bottomright';

        coordinate = _formatCoordinate(coordinate || [0, 0]);
        options.offset = _formatCoordinate(options.offset || [0, 0]);
    
        coordinate.x += (options.position.indexOf('right') >= 0 ? (coordinate.width || 0) : 0); 
        coordinate.y += (options.position.indexOf('bottom') >= 0 ? (coordinate.height || 0) : 0); 
        
        elementStyle.left = coordinate.x + options.offset.x - parentPos.left;
        elementStyle.top = coordinate.y + options.offset.y - parentPos.top;

        switch (options.insideScreen) {
           case "surround" :
                elementStyle.left += elementStyle.left < scrollLeft ? sourceWidth  + (coordinate.width || 0): 
                                    ((elementStyle.left + sourceWidth ) > (scrollLeft + cW) ? - sourceWidth - (coordinate.width || 0) : 0);
                elementStyle.top  += elementStyle.top  < scrollTop  ? sourceHeight  + (coordinate.height || 0):
                                    ((elementStyle.top  + sourceHeight) > (scrollTop  + cH) ? - sourceHeight - (coordinate.height || 0) : 0);
                break;
            case 'fix' :
                elementStyle.left = Math.max(
                        0 - parseFloat(baidu.dom.getStyle(source, 'marginLeft')) || 0,
                        Math.min(
                            elementStyle.left,
                            baidu.page.getViewWidth() - sourceWidth - parentPos.left
                            )
                        );
                elementStyle.top = Math.max(
                        0 - parseFloat(baidu.dom.getStyle(source, 'marginTop')) || 0,
                        Math.min(
                            elementStyle.top,
                            baidu.page.getViewHeight() - sourceHeight - parentPos.top
                            )
                        );
                break;
            case 'verge':
                var offset = {
                    width: (options.position.indexOf('right') > -1 ? coordinate.width : 0),//是否放在原点的下方
                    height: (options.position.indexOf('bottom') > -1 ? coordinate.height : 0)//是否放在原点的右方
                },
                optOffset = {
                    width: (options.position.indexOf('bottom') > -1 ? coordinate.width : 0),
                    height: (options.position.indexOf('right') > -1 ? coordinate.height : 0)
                };
               
                elementStyle.left -= (options.position.indexOf('right') >= 0 ? (coordinate.width || 0) : 0);
                elementStyle.top -= (options.position.indexOf('bottom') >= 0 ? (coordinate.height || 0) : 0);
                
                elementStyle.left += elementStyle.left + offset.width + sourceWidth - scrollLeft > cW - parentPos.left ?
                    optOffset.width - sourceWidth : offset.width;
                elementStyle.top += elementStyle.top + offset.height + sourceHeight - scrollTop > cH - parentPos.top ?
                    optOffset.height - sourceHeight : offset.height;
                break;
        }
        baidu.dom.setPosition(source, elementStyle);


        //如果因为调整位置令窗口产生了滚动条，重新调整一次。
        //可能出现死循环，用_scrollJustify保证重新调整仅限一次。
        if (!_scrollJustify && (cH != baidu.page.getViewHeight() || cW != baidu.page.getViewWidth())) {
            me._positionByCoordinate(source, coordinate, {}, true);
        }
        _scrollJustify || me.dispatchEvent('onpositionupdate');
    };

    /**
     * 根据参数不同，选择执行一次或者在window resize的时候再次执行某方法
     * @private
     *
     * @param {HTMLElement|string} element 根据此元素寻找window.
     * @param {string} fnName 方法名，会在this下寻找.
     * @param {Boolean} once 是否只执行一次.
     * @return {arguments} args 执行方法的参数.
     */
    Posable.__execPosFn = function(element, fnName, once, args) {
        var me = this;

        if (typeof once == 'undefined' || !once) {
            baidu.event.on(
                baidu.dom.getWindow(element),
                'resize',
                baidu.fn.bind.apply(me, [fnName, me].concat([].slice.call(args)))
            );
        }
        me[fnName].apply(me, args);
    };
    /**
     * 格式化坐标格式
     * @param {Object|array} coordinate 要调整的坐标格式.
     * @return {Object} coordinate 调整后的格式
     * 类似：{x : number, y : number}.
     */
    function _formatCoordinate(coordinate) {
        coordinate.x = coordinate[0] || coordinate.x || coordinate.left || 0;
        coordinate.y = coordinate[1] || coordinate.y || coordinate.top || 0;
        return coordinate;
    }
})();



/**
 * 为Suggestion提供位置校准功能
 * @name  baidu.ui.Suggestion.Suggestion$fixWidth
 * @addon baidu.ui.Suggestion
 * @author berg
 */
baidu.ui.Suggestion.extend({
    posable: true,
    fixWidth: true,
    getWindowResizeHandler: function() {
        var me = this;
        return function() {
            me.adjustPosition(true);
        };
    },

	/*
     * 重新放置suggestion
     * @private
     */
    adjustPosition: function(onlyAdjustShown) {
       var me = this,
            target = me.getTarget(),
            targetPosition,
            main = me.getMain(),
            pos;

        if (!me._isShowing() && onlyAdjustShown) {
            return;
        }
        targetPosition = baidu.dom.getPosition(target),
        pos = {
                top: (targetPosition.top + target.offsetHeight - 1),
                left: targetPosition.left,
                width: target.offsetWidth
            };
        //交给用户的view函数计算
        pos = typeof me.view == 'function' ? me.view(pos) : pos;

        me.setPosition([pos.left, pos.top], null, {once: true});
        baidu.dom.setOuterWidth(main, pos.width);
    }
});
baidu.ui.Suggestion.register(function(me) {

    me.windowResizeHandler = me.getWindowResizeHandler();

    me.addEventListener('onload', function() {
        me.adjustPosition();
        //监听搜索框与suggestion弹出层的宽度是否一致。
        if (me.fixWidth) {
            me.fixWidthTimer = setInterval(function() {
                var main = me.getMain(),
                    target = me.getTarget();
                if (main.offsetWidth != 0 && target && target.offsetWidth != main.offsetWidth) {
                    me.adjustPosition();
                    main.style.display = 'block';
                }
            }, 100);
        }
        //当窗口变化的时候重新放置
        me.on(window, 'resize', me.windowResizeHandler);
    });

    //每次出现的时候都重新定位，保证用户在初始化之后修改了input的位置，也不会出现混乱
    me.addEventListener('onshow', function() {
        me.adjustPosition();
    });

    me.addEventListener('ondispose', function() {
        clearInterval(me.fixWidthTimer);
    });

});

/**
 * 支持快捷键操作，如上下，回车等
 * @name  baidu.ui.Suggestion.Suggestion$input
 * @addon baidu.ui.Suggestion
 */
baidu.ui.Suggestion.register(function(me) {
    var target,

        //每次轮询获得的value
        oldValue = '',

        //一打开页面就有的input value
        keyValue,

        //使用pick方法上框的input value
        pickValue,
        mousedownView = false,
        stopCircleTemporary = false;
    
    function initKeyValue(){
        setTimeout(function(){//防止opera和ie回退时自动打开sug
            keyValue = me.getTarget().value;
        }, 20);
    }

    me.addEventListener('onload', function() {
        target = this.getTarget();

        initKeyValue();
        
        me.on(window, 'onload', initKeyValue);

        //生成dom事件函数
        me.targetKeydownHandler = me.getTargetKeydownHandler();

        //加入dom事件
        me.on(target, 'keydown', me.targetKeydownHandler);

        target.setAttribute('autocomplete', 'off');

        //轮询计时器
        me.circleTimer = setInterval(function() {
            if (stopCircleTemporary) {
                return;
            }

            if (baidu.g(target) == null) {
                me.dispose();
            }

            var nowValue = target.value;
            //todo,这里的流程可以再简化一点
            if (
                nowValue == oldValue &&
                nowValue != '' &&
                nowValue != keyValue &&
                nowValue != pickValue
              ) {
                if (me.requestTimer == 0) {
                    me.requestTimer = setTimeout(function() {
                        me.dispatchEvent('onneeddata', nowValue);
                    }, 100);
                }
            }else {
                clearTimeout(me.requestTimer);
                me.requestTimer = 0;
                if (nowValue == '' && oldValue != '') {
                    pickValue = '';
                    me.hide();
                }
                oldValue = nowValue;
                if (nowValue != pickValue) {
                    me.defaultIptValue = nowValue;
                }
                if (keyValue != target.value) {
                    keyValue = '';
                }
            }
        }, 10);

        me.on(target, 'beforedeactivate', me.beforedeactivateHandler);
    });

    me.addEventListener('onitemclick', function() {
        stopCircleTemporary = false;
        //更新oldValue，否则circle的时候会再次出现suggestion
        me.defaultIptValue = oldValue = me.getTargetValue();
    });

    me.addEventListener('onpick', function(event) {
        //firefox2.0和搜狗输入法的冲突
        if (mousedownView)
            target.blur();
        target.value = pickValue = event.data.item.value;
        if (mousedownView)
            target.focus();
    });

    me.addEventListener('onmousedownitem', function(e) {
        mousedownView = true;
        //chrome和搜狗输入法冲突的问题
        //在chrome下面，输入到一半的字会进框，如果这个时候点击一下suggestion，就会清空里面的东西，导致suggestion重新被刷新
        stopCircleTemporary = true;
        setTimeout(function() {
            stopCircleTemporary = false;
            mousedownView = false;
        },500);
    });
    me.addEventListener('ondispose', function() {
        clearInterval(me.circleTimer);
    });
});

baidu.ui.Suggestion.extend({
    /*
     * IE和M$输入法打架的问题
     * 在失去焦点的时候，如果是点击在了suggestion上面，那就取消其默认动作(默认动作会把字上屏)
     */
    beforedeactivateHandler: function() {
        return function() {
            if (mousedownView) {
                window.event.cancelBubble = true;
                window.event.returnValue = false;
            }
        };
    },

    getTargetKeydownHandler: function() {
        var me = this;

        /*
         * 上下键对suggestion的处理
         */
        function keyUpDown(up) {

            if (!me._isShowing()) {
                me.dispatchEvent('onneeddata', me.getTargetValue());
                return;
            }

            var enableIndex = me.enableIndex,
                currentIndex = me.currentIndex;

            //当所有的data都处于disable状态。直接返回
            if (enableIndex.length == 0) return;
            if (up) {
                switch (currentIndex) {
                    case -1:
                        currentIndex = enableIndex.length - 1;
                        me.pick(enableIndex[currentIndex]);
                        me.highLight(enableIndex[currentIndex]);
                        break;
                    case 0:
                        currentIndex = -1;
                        me.pick(me.defaultIptValue);
                        me.clearHighLight();
                        break;
                    default:
                        currentIndex--;
                        me.pick(enableIndex[currentIndex]);
                        me.highLight(enableIndex[currentIndex]);
                        break;
                }
            }else {
                switch (currentIndex) {
                    case -1:
                        currentIndex = 0;
                        me.pick(enableIndex[currentIndex]);
                        me.highLight(enableIndex[currentIndex]);
                        break;
                    case enableIndex.length - 1:
                        currentIndex = -1;
                        me.pick(me.defaultIptValue);
                        me.clearHighLight();
                        break;
                    default:
                        currentIndex++;
                        me.pick(enableIndex[currentIndex]);
                        me.highLight(enableIndex[currentIndex]);
                        break;
                }
            }
            me.currentIndex = currentIndex;
        }
        return function(e) {
            var up = false, index;
            e = e || window.event;
            switch (e.keyCode) {
                case 9:     //tab
                case 27:    //esc
                    me.hide();
                    break;
                case 13:    //回车，默认为表单提交
                    baidu.event.stop(e);
                    me.confirm( me.currentIndex == -1 ? me.getTarget().value : me.enableIndex[me.currentIndex], 'keyboard');
                    break;
                case 38:    //向上，在firefox下，按上会出现光标左移的现象
                    up = true;
                case 40:    //向下
                    baidu.event.stop(e);
                    keyUpDown(up);
                    break;
                default:
                   me.currentIndex = -1;
            }
        };
    },

    /*
     * pick选择之外的oldValue
     */
    defaultIptValue: ''

});
;T.undope=true;