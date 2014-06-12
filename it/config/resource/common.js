
    /********************common********************/
    function endWith(str,endStr)
    {
        var ret=false;
        var i=0;
        if(str.length <endStr.length)
        {
            ret=false;
            i=1;
        }
        else if(str==endStr)
        {
            ret=true;
            i=2;
        }
        else if(str.substring(str.length-endStr.length)==endStr)
        {
            ret=true;
            i=3;
        }
        return ret;
    }

    function getElementsByClassName(oElm, strTagName, strClassName)
    {
        var arrElements = (strTagName == "*" && oElm.all)? oElm.all : oElm.getElementsByTagName(strTagName);
        var arrReturnElements = new Array();
        strClassName = strClassName.replace(/\-/g, "\\-");
        var oRegExp = new RegExp("(^|\\s)" + strClassName + "(\\s|$)");
        var oElement;
        for(var i=0; i < arrElements.length; i++)
        {
            oElement = arrElements[i];
            if(oRegExp.test(oElement.className))
            {
                arrReturnElements.push(oElement);
            }
        }
        return (arrReturnElements)
    }

    function setStoreyBodyFontSize(size)
    {
      var divStoreies = document.getElementsByName("storey_body");
      for(var i = 0; i < divStoreies.length; i++)
      {
          if (divStoreies[i].nodeType==1)
          {
              divStoreies[i].style.fontSize = size + "px";
          }
      }
    }

/*
    function elementProperties2(x,y)
    {
        var element=elementFromViewportPoint(x,y);
        
        var str="{";
        for (var property in element)
        {
            if (typeof(element[property])!="function" && property!="outerHTML")
            {
                str+="\""+property+"\":\""+element[property]+"\",";
            }
        }
        
        var attributes=element.attributes;
        for (var i in attributes)
        {
            if (typeof(attributes[i])!="function")
            {
                str+="\""+attributes[i].nodeName+"\":\""+attributes[i].nodeValue+"\",";
            }
        }
        
        //自定义添加的
        var parentTagName=element["parentElement"].tagName;
        str+="\"parentTagName\":\""+parentTagName+"\"";
        //str=str.substr(0,str.length-1);
        
        str+="}";
        
        return str;
    }
 */

    function isParent (obj,parentObj)
    {
        while (obj != undefined && obj != null && obj.tagName.toUpperCase() != 'BODY')
        {
            if (obj === parentObj)
            {
                return true;
            }
            obj = obj.parentElement;
        }
        return false;
    }

    function elementProperties(x,y)
    {
        var element=elementFromViewportPoint(x,y);
        
        var sel=window.getSelection();
        var focusNode=sel.focusNode;
        if(focusNode!=null)
        {
            var commonAncestorContainer=sel.getRangeAt(0).commonAncestorContainer;

            if(element==focusNode || element==commonAncestorContainer || isParent(element,commonAncestorContainer))
            {
                return "";
            }
        }
        
        var str="{";
        
        var attributes=element.attributes;
        for (var i in attributes)
        {
            if (typeof(attributes[i])!="function")
            {
                str+="\""+attributes[i].nodeName+"\":\""+attributes[i].nodeValue+"\",";
            }
        }

        str+="\"innerHTML\":\""+replaceText(element["innerHTML"])+"\",";
        str+="\"tagName\":\""+element["tagName"]+"\",";
        str+="\"parentTagName\":\""+element["parentElement"].tagName+"\"";

        str+="}";
        
        return str;
    }

    function getTitleBodyText()
    {
        var title=document.getElementById("title").innerHTML;
        var body=document.getElementById("body").innerHTML;
        
        var section_ptn = /<\/(p|div)[^>]*>/g;//过滤标签开头
        var tag_ptn = /<\/?[^>]*>/g;      //过滤标签开头
        var space_ptn = /&nbsp;/ig;       //过滤标签结尾
        
        var text = body.replace(section_ptn,"\n").replace(tag_ptn,"").replace(space_ptn," ");
        
        text=replaceText(text);
        
        return title+"\\n\\n"+text;
    }

    function getAllShareContents()
    {
        var content=getTitleBodyText();
             
        var str='[';
        str+='{"type":"text","content":"'+content+'"}';
        var elements = document.getElementsByTagName("img");
                          
        var length=elements.length;
        for(var i=0;i<length;i++)
        {
            var path=replaceImgSrc(elements[i].src);
            str+=',{"type":"img","content":"'+path+'"}';
        }
                          
        str+=']';
      
        return str;
    }

                      
    function getSelectedShareContents()
    {
        var text=window.getSelection().toString();
        text=replaceText(text);
        
        var arr=new Array();
                          
        var fragment = window.getSelection().getRangeAt(0).cloneContents();
        var d = document.createElement("div");
        d.appendChild(fragment);
        getSubImgSrcs(d,arr);
                          
        var str='[';
        str+='{"type":"text","content":"'+text+'"}';
        for(var i=0;i<arr.length;i++)
        {
            str+=',{"type":"img","content":"'+arr[i]+'"}';
        }
        str+=']';
        
        return str;
    }

    function getSubImgSrcs(element,arr)
    {
        if(element.nodeType!=1)
        {
            return;
        }
                          
        if(element.hasChildNodes)
        {
            var childNodes=element.childNodes;
            var length=childNodes.length;
            for(var i=0;i<length;i++)
            {
                var child=childNodes[i];
                if(child.tagName && child.tagName!=undefined)
                {
                    if(child.tagName.toLowerCase()=='img')
                    {
                        var path=replaceImgSrc(child.src);
                        arr.push(path);
                    }
                    else
                    {
                        getSubImgSrcs(child,arr);
                    }
                }
            }
        }
    }
                          
    function replaceText(text)
  {
      text=text.replace(/\n/g,'\\n');
      text=text.replace(/\r/g,'\\r');
      text=text.replace(/\t/g,'\\t');
      text=text.replace(/\"/g,'\\"');
                        
      return text;
  }
                          
    function replaceImgSrc(src)
  {
    var path=src.replace(/\?t=\d+$/,"");
    path=path.replace(/^file:\/\//,"");
    path=path.replace(/%20/g," ");
                     
    return path;
  }

    function viewportCoordinateToDocumentCoordinate(x,y)
    {
        var coord = new Object();
        coord.x = x + window.pageXOffset;
        coord.y = y + window.pageYOffset;
        return coord;
    }
    
    function elementFromPointIsUsingViewPortCoordinates()
    {
        if (window.pageYOffset > 0)
        {
            // page scrolled down
            return (window.document.elementFromPoint(0, window.pageYOffset + window.innerHeight -1) == null);
        }
        else if (window.pageXOffset > 0)
        {
            // page scrolled to the right
            return (window.document.elementFromPoint(window.pageXOffset + window.innerWidth -1, 0) == null);
        }
        
        return false; // no scrolling, don't care
    }
    
    function elementFromViewportPoint(x,y)
    {
        return window.document.elementFromPoint(x,y);
        
        /*
        if (elementFromPointIsUsingViewPortCoordinates())
        {
            return window.document.elementFromPoint(x,y);
        } 
        else 
        {
            var coord = viewportCoordinateToDocumentCoordinate(x,y);
            return window.document.elementFromPoint(coord.x,coord.y);
        }
         */
    }

    function getStyle(sname)
    {
        for (var i=0;i<document.styleSheets.length;i++)
        {
            var rules;
            
            if (document.styleSheets[i].cssRules)
            {
                rules = document.styleSheets[i].cssRules;
            }
            else
            {
                rules = document.styleSheets[i].rules;
            }
            
            for (var j=0;j<rules.length;j++)
            {
                if (rules[j].selectorText == sname)
                {
                    return rules[j].style;
                }
            }
        }
    }

    //全局变量
    var gImageCount=-1;
    var gPageOffsetY=0;

    function setPageOffsetY(offsetY,imageCount)
    {
        gPageOffsetY=offsetY;
        gImageCount=imageCount;
    }

    function checkSetPageOffsetY()
    {
        gImageCount--;
        
        if(gImageCount==0)
        {
            window.scrollTo(0,gPageOffsetY);
            gPageOffsetY=0;
        }
    }

    function imgOnError(img)
    {
        //出现加载图片出错的网页
        //http://www.cnblogs.com/feng524822/p/3312171.html
        //http://www.eoe.cn/news/16161.html
        img.src=img.getAttribute("error_src");
        img.style.border="1px solid red";
        
        checkSetPageOffsetY();
    }
    
  function localImgOnload(img)
  {
       var ret=endWith(img.src,"@2x.png");
       var scaled=img.getAttribute("scaled");//设置这个属性保存是否已缩放过，防止重复缩放
       if(ret && scaled!="true")
       {
           img.width=img.width/2;//不用再设置 img.height=img.height/2;
           img.setAttribute("scaled","true");
           img.style.visibility="visible";
       }
   }
   
    function imgOnload(img)
    {
        img.style.display="block";
        /*
        var ret=endWith(img.src,"@2x.png");
        var scaled=img.getAttribute("scaled");//设置这个属性保存是否已缩放过，防止重复缩放
        if(ret && scaled!="true")
        {
            img.width=img.width/2;//不用再设置 img.height=img.height/2;
            img.setAttribute("scaled","true");
            img.style.visibility="visible";
        }
        */

        var maxWidth=280;

        //check if in table
        var pNode=img.parentNode;
        while(pNode.tagName.toLowerCase()!="body")
        {
            pNode=pNode.parentNode;

            if(pNode.tagName.toLowerCase()=="table" || pNode.tagName.toLowerCase()=="tbody")
            {
                var columns=pNode.rows.item(0).cells.length;
                if(columns>0)
                {
                    var columnWidth=maxWidth/columns-2*columns;
                    if(img.width>columnWidth)
                    {
                        img.width=columnWidth;
                    }
                }
                
                break;
            }
            else if(pNode.tagName.toLowerCase()=="p")
            {
                //http://news.ipadown.com/28679
                //测试不需要做下面的动态操作了
                /*pNode.style.textAlign="center";
                pNode.style.textIndent="0em";*/
                
                break;
            }
        }

        if(img.width>maxWidth)
        {
            img.width=maxWidth;
        }
        
        checkSetPageOffsetY();
    }

    function videoOnloadstart(video)
    {
        //checkSetPageOffsetY();
    }

    /******************************************************************/
    
    function setImgSrcByName(imgNodeName,src)
    {
        var images = document.getElementsByName(imgNodeName);
        for(var i = 0; i < images.length; i++)
        {
            var theImage=images[i];
            if(theImage.tagName.toLowerCase()=="video")
            {
                images[i].poster=src;
            }
            else
            {
                images[i].src=src;
            }
            
            //images[i].setAttribute("src_temp","");
            
            /*
            //new node
            var img = new Image(); //创建一个Image对象，实现图片的预下载
            img.setAttribute("src_temp","");
            img.setAttribute("src_large",theImage.getAttribute("src_large"));
            img.src=src;
            img.name=theImage.name;
            img.onload = theImage.onload;
            img.className=theImage.className;
  
            //or cloneNode
            var img=theImage.cloneNode();
            //img.setAttribute("src_temp","");
            img.setAttribute("src_large",theImage.getAttribute("src_large"));
            img.src=src;


            theImage.parentNode.replaceChild(img,theImage);//在旧新图片之间，会出现极小化的旧图片闪过的现象。
            */
        }
    }
    
    function setFontSize(fontSize)
    {
        //globolObj=elementFromViewportPoint(155,50);
        
        var selector=getStyle(".articleBody");
        selector.fontSize = fontSize+"px";
        
        selector=getStyle(".articleBottom");
        selector.fontSize = (fontSize-2)+"px";
        
        selector=getStyle(".articleTopSpan");
        selector.fontSize = (fontSize-4)+"px";
        
        selector=getStyle(".articleHead");
        selector.fontSize = (fontSize+5)+"px";
    }
    
    function setStyles(headFontColor,headBgColor,bodyFontColor,bodyBgColor,imageOpacity,linkColor,procedureBgColor)
    {
        var selector=getStyle(".articleTop");
        selector.backgroundColor=headBgColor;
        
        selector=getStyle(".articleTopDiv1");
        selector.backgroundColor=bodyBgColor;
        
        selector=getStyle(".articleTopSpan");
        selector.color = bodyFontColor;
        selector.backgroundColor=bodyBgColor;
        
        selector=getStyle(".articleHead");
        selector.color = headFontColor;
        selector.backgroundColor=headBgColor;
        
        selector=getStyle(".articleSubHead");
        selector.color = headFontColor;
        selector.backgroundColor=headBgColor;

        selector=getStyle(".articleBody");
        selector.color=bodyFontColor;
        //selector.backgroundColor=bodyBgColor;
        
        selector=getStyle(".articleBottom");
        selector.color=bodyFontColor;
        //selector.backgroundColor=bodyBgColor;
        
        selector=getStyle("img");//.bigImg
        selector.opacity=imageOpacity;
        selector=getStyle("video");
        selector.opacity=imageOpacity;
        
        selector=getStyle("a");
        selector.color=linkColor;
        
        selector=getStyle("pre");
        selector.backgroundColor=procedureBgColor;
        
        selector=getStyle(".dp-cpp");
        selector.backgroundColor=procedureBgColor;
                      
        selector=getStyle(".codebody");
        selector.backgroundColor=procedureBgColor;
    }
    
    function setReadTimes(read_times)
    {
        var element=document.getElementById("read_times");
          if(read_times!=undefined && read_times>0)
          {
                element.innerHTML=read_times;
                element.style.display="inline";
          }
          else
          {
                element.innerHTML=0;
                element.style.display="none";
          }
    }
                      
    function setTitleBodyBottom(categoryName,title,datetime,page_number,body,bottom,read_times)
    {
        window.scrollTo(0,0);
        
        var element=document.getElementById("topSpan");
        element.innerHTML=categoryName;

        element=document.getElementById("title");
        element.innerHTML=title;
        
        element=document.getElementById("datetime");
        if(datetime=="")
        {
            datetime="&nbsp;";
        }
        element.innerHTML=datetime;

        setReadTimes(read_times);
                      
        element=document.getElementById("page_number");
        element.innerHTML=page_number;
    
        //delete temp <hidden> between table tr td
        body=body.replace(/(<table.*?>)\s*<hidden>\s*<tr>/g, "$1<tr>");
        body=body.replace(/<tr>\s*<hidden>\s*<td>/g, "<tr><td>");
        body=body.replace(/<\/tr>\s*<hidden>\s*<tr>/g, "</tr><tr>");
        body=body.replace(/<\/td>\s*<hidden>\s*<\/tr>/g, "</td></tr>");
        body=body.replace(/<\/tr>\s*<hidden>\s*<\/table>/g, "</tr></table>");
        
        element=document.getElementById("body");
        element.innerHTML=body;
        
        element=document.getElementById("bottom");
        element.innerHTML=bottom;
        
        formatCodeSection("<hidden>");
    }

    function formatCodeSection(seperator)
    {
        var elements=document.getElementsByClassName("brush: js");
        var elements2=document.getElementsByClassName("brush: html");
        var elements3=document.getElementsByClassName("brush: css");
        var elements4=document.getElementsByTagName("pre");
        
        formatCodeLinesWithTagLi(elements,seperator);
        formatCodeLinesWithTagLi(elements2,seperator);
        formatCodeLinesWithTagLi(elements3,seperator);
        formatCodeLinesWithRN(elements4,seperator);
    }
    
    function formatCodeLinesWithTagLi(elements,seperator)
    {
        for(var i=0;i<elements.length;i++)
        {
            var newContent="<ol>";
            
            var lines = elements[i].innerHTML.split(seperator);
            
            for (var j=0,len=lines.length; j<len; j++)
            {
                newContent=newContent+"<li>"+lines[j]+"</li>";
            }
            
            newContent=newContent+"</ol>";
            
            elements[i].innerHTML=newContent;
        }
    }
    
    function formatCodeLinesWithRN(elements,seperator)
    {
        for(var i=0;i<elements.length;i++)
        {
            var newContent="";
            
            var lines = elements[i].innerHTML.split(seperator);
            
            for (var j=0,len=lines.length; j<len; j++)
            {
                newContent=newContent+lines[j]+"\r\n";
            }
            
            if(newContent.length>0)
            {
                newContent=newContent.substring(0,newContent.length-2);
            }
            
            elements[i].innerHTML=newContent;
        }
    }
    
    function afterLoad()
    {
        ////var selector=getStyle(".articleBody");
        ////var fontSize=selector.fontSize;
        ////setFontSize(parseInt(fontSize));
        
        formatCodeSection("\n");
        
        var videos = document.querySelectorAll("video");
        for (var i=0,len=videos.length; i<len; i++)
        {
            videos[i].autoplay=undefined;
        }
        
        var element=document.getElementById("read_times");
        if(element.style.display=="none" && parseInt(element.innerHTML)>0)
        {
            element.style.display="inline";
        }
                      
        /*
        //http://www.cnblogs.com/newyorker/archive/2013/02/14/2891298.html
        var cells = document.querySelectorAll(".articleBody> div:only-child > div:only-child > table:only-child td");
        for (var j=0,len=cells.length; j<len; j++)
        {
            cells[j].style.borderWidth=0;
        }
         */
        
        //alert(navigator.appVersion);
        //alert(navigator.userAgent);
        /*
        if(navigator.userAgent.match(/OS 7_\d[_\d]* like Mac OS X/ig))
        {
            document.getElementById("foot").style.display="block";
        }
         */
    }