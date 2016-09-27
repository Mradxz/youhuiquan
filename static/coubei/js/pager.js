/*
 * 原生的JavaScript代码编写. 重写onclick方法, 获取翻页事件,
 * 可用来向服务器端发起AJAX请求
 * <div id="pager"></div>
 * var pager = new PagerView('pager');
 * pager.index = 1; // 当前是第1页
 * pager.size = 16; // 每页显示16条记录
 * pager.itemCount = 100; // 一共有100条记录
 *
 * pager.onclick = function(index){
 * alert('click on page: ' + index);
 * // display data...
 * pager.render();
 * };
 *
 * pager.render();
 *
 * @param id: HTML节点的id属性值, 控件将显示在该节点中. 可以延迟到render方法调用之前设置.
 */
function PagerView(id){
	 var self = this;
	 this.id = id;
	 this.container = null;
	 this.index = 1;      // 当前页码, 从1开始
	 this.size = 15;      // 每页显示记录数
	 this.maxButtons = 9; // 显示的分页按钮数量
	 this.itemCount = 0;  // 记录总数
	 this.pageCount = 0;  // 总页数
	 /*
		控件使用者重写本方法, 
		获取翻页事件, 
		可用来向服务器端发起ajax请求.
		@param index: 被点击的页码.
	  */
	 this.onclick = function(index){
		
	 };
	 /*
	  * 内部方法.
	  */
	 this._onclick = function(index){
	  self.index = index;
	  self.onclick(index);
	  self.render();
	 };
	 /**
	  * 在显示之前计算各种页码变量的值.
	  */
	 this.calculate = function(){
	  self.pageCount = parseInt(Math.ceil(self.itemCount / self.size));
	  self.index = parseInt(self.index);
	  if(self.index > self.pageCount){
	   self.index = self.pageCount;
	  }
	 };
	 /**
	  * 渲染分页控件.
	  */
	 this.render = function(){
	  if(self.id != undefined){
	   var div = document.getElementById(self.id);
	   div.view = self;
	   self.container = div;
	  }
	  self.calculate();
	  var start, end;
	  start = Math.max(1, self.index - parseInt(self.maxButtons/2));
	  end = Math.min(self.pageCount, start + self.maxButtons - 1);
	  start = Math.max(1, end - self.maxButtons + 1);
	  var str = "";
	  str += "<div class=\"PagerView\">\n";
	  if(self.pageCount > 1){
	   if(self.index != 1){
		str += '<a href="javascript://1"><span>|<</span></a>';
		str += '<a href="javascript://' + (self.index-1) + '"><span><<</span></a>';
	   }else{
		str += '<span>|<</span>';
		str += '<span><<</span>';
	   }
	  }
	  for(var i=start; i<=end; i++){
	   if(i == this.index){
		str += '<span class="on">' + i + "</span>";
	   }else{
		str += '<a href="javascript://' + i + '"><span>' + i + "</span></a>";
	   }
	  }
	  if(self.pageCount > 1){
	   if(self.index != self.pageCount){
		str += '<a href="javascript://' + (self.index+1) + '"><span>>></span></a>';
		str += '<a href="javascript://' + self.pageCount + '"><span>>|</span></a>';
	   }else{
		str += '<span>>></span>';
		str += '<span>>|</span>';
	   }
	  }
	  str += ' 一共' + self.pageCount + '页, ' + self.itemCount + '条记录 ';
	  str += "</div>";
	  self.container.innerHTML = str;
	  var a_list = self.container.getElementsByTagName('a');
	  for(var i=0; i<a_list.length; i++){
	   a_list[i].onclick = function(){
		var index = this.getAttribute('href');
		if(index != undefined && index != ''){
		 index = parseInt(index.replace('javascript://', ''));
		 self._onclick(index)
		}
		return false;
	   };
	  }
	 };
}