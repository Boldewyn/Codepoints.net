require(["jquery","jquery.ui"],function(e){e(".extended.searchform").each(function(){function f(t,n,r){var i=e('<ul class="query-choose"></ul>');e.each(n,function(n,s){i.append(e('<li><a class="button" href="#">'+s[1]+"</a></li>").on("click tap","a",function(){return r(t,s[1],s[0]),i.dialog("close").dialog("destroy").remove(),i=null,!1}))}),i.dialog({title:t,modal:!0,width:Math.min(e(window).width(),1030)})}function l(t,n,r){var s="",o=jQuery.noop;return r.is("select")?(s=" y",o=function(){return r.find('option[value=""]')[0].selected=!1,r.find('option[value="1"]')[0].selected?(r.find('option[value="1"]')[0].selected=!1,r.find('option[value="0"]')[0].selected=!0,e(this).find(".value").addClass("n").removeClass("y")):(r.find('option[value="0"]')[0].selected=!1,r.find('option[value="1"]')[0].selected=!0,e(this).find(".value").addClass("y").removeClass("n")),!1}):o=function(){var n=e(this),i=u[t];f(t,i,function(e,t,i){n.find(".value").text(t),r[0].checked=!1,i[0].checked=!0})},e('<li class="query-item"></li>').html('<span class="key">'+t+':</span> <span class="value'+s+'" title="click to change">'+n+'</span> <button type="button">remove</button>').find("button").button({text:!1,icons:{primary:"ui-icon-close",secondary:!1}}).on("click tab",function(){var t=e(this).closest("li");return r.is(":checkbox")?r[0].checked=!1:r.find('option[value=""]')[0].selected=!0,t.slideUp("fast",t.remove),!1}).end().on("click tab",o).tooltip().appendTo(i)}var t=e(this),n=e(".propsearch, .boolsearch",t).hide(),r=e(".submitset",t),i=e('<ul class="query-add ui-widget ui-widget-content ui-corner-all"></ul>').insertBefore(r),s=e(),o=e('<p><button type="button">+ add new query</button></p>').insertBefore(r).find("button"),u={},a=e('<ul class="ui-menu ui-widget ui-widget-content" tabindex="0"></ul>');n.filter(".propsearch").each(function(){var t=e(this),n=[],r=e("legend",t).text().replace(/:\s*$/,"");e(":checkbox",t).each(function(){var i=e(this),s=e('label[for="'+this.id+'"]',t).text();n.push([i,s]),i[0].checked&&l(r,s,i)}),u[r]=n}),u["Other Property"]=[],n.filter(".boolsearch").each(function(){var t=e("select",this),n=e("label",this).text();u["Other Property"].push([t,n]),t.find('option[value="1"]')[0].selected&&l("Other Property",n,t)}),e.each(u,function(t,n){a.append(e('<li class="ui-menu-item"><a href="#">'+t+"</a></li>").data("v",n).data("k",t))}),e(document).on("keydown click tap",function(){a.slideUp()}),a.css({display:"none",position:"absolute"}).appendTo("body").on("click tap","li",function(){var t=e(this);return f(t.data("k"),t.data("v"),function(e,t,n){n.is(":checkbox")?n[0].checked=!0:n.find('option[value="1"]')[0].selected=!0,l(e,t,n)}),a.slideUp(),!1}).on("keydown",function(t){if(!t.shiftKey&&!t.metaKey&&!t.ctrlKey&&!t.altKey){var n=a.find("a:focus"),r=e();switch(t.which){case 38:n.length&&(r=n.parent().prev("li").find("a")),r.length||(r=a.find("a:last"));break;case 40:n.length&&(r=n.parent().next("li").find("a")),r.length||(r=a.find("a:first"))}if(r.length)return r.focus(),t.preventDefault(),t.stopPropagation(),!1}}),o.on("click tap",function(){return a.show().position({my:"left top",at:"left bottom",of:this,collision:"fit fit"}).hide().slideDown().focus(),!1}),i.on("click tap",function(e){if(e.target===this)return o.trigger("click"),!1})})}),define("searchform",function(){});