/* initialize advanced comment system */
function ACS_init(){
  /* initialize the comment link */
  if(ACS_getCookie("ACS_HideComments")=="yes"){
    ACS_show(document.getElementById("ACS_Comments_Show"));
  }else{
    ACS_show(document.getElementById("ACS_Comments_Hide"));
    ACS_show(document.getElementById("ACS_Comments"));
  }
  
  /* set name for comment form from cookie */
  var ACS_CommentName = ACS_getCookie("ACS_CommentName");
  
  if(ACS_CommentName){
    document.getElementById("ACS_newCommentName").value = ACS_CommentName;
  }
  
  var ACS_newCommentSliderEnabled = document.getElementById("ACS_newCommentSliderEnabled");
  
  if(ACS_newCommentSliderEnabled && ACS_newCommentSliderEnabled.value=="1"){
    /* initialize the slider */
    new Slider(document.getElementById("ACS_slider"),document.getElementById("ACS_sliderKnob"),{
      steps: 1,
      range: [0],
      onChange: function(value){
        document.getElementById("ACS_newCommentSlider").value = value;
      
        if(value==1){
          document.getElementById("ACS_sliderKnob").style.backgroundColor = "rgb(121,148,112)";
        }else{
          document.getElementById("ACS_sliderKnob").style.backgroundColor = "rgb(229,135,18)";
        }
      }
    });
  }
}

/* submit comments form */
function ACS_submitComment(){
  var ACS_newCommentName = document.getElementById("ACS_newCommentName");
  var ACS_newCommentMessage = document.getElementById("ACS_newCommentMessage");
  var ACS_newCommentAntiSpamCode = document.getElementById("ACS_newCommentAntiSpamCode");
  var ACS_newCommentAntiSpamCodeVerification = document.getElementById("ACS_newCommentAntiSpamCodeVerification");
  var ACS_newCommentAntiSpamCodeEnabled = document.getElementById("ACS_newCommentAntiSpamCodeEnabled");
  var ACS_newCommentSubmit = document.getElementById("ACS_newCommentSubmit");
  var ACS_newCommentRememberName = document.getElementById("ACS_newCommentRememberName");
  var ACS_newCommentSliderEnabled = document.getElementById("ACS_newCommentSliderEnabled");
  var ACS_newCommentSlider = document.getElementById("ACS_newCommentSlider");
  var ACS_newCommentNameMinLength = document.getElementById("ACS_newCommentNameMinLength");
  var ACS_newCommentNameMaxLength = document.getElementById("ACS_newCommentNameMaxLength");
  var ACS_newCommentMessageMinLength = document.getElementById("ACS_newCommentMessageMinLength");
  var ACS_newCommentMessageMaxLength = document.getElementById("ACS_newCommentMessageMaxLength");
  
  /* check if name is ok */
  if(!ACS_newCommentName || ACS_newCommentName.value.length<ACS_newCommentNameMinLength.value || ACS_newCommentName.value.length>ACS_newCommentNameMaxLength.value){
    ACS_newCommentName.focus();
    ACS_changeClass(ACS_newCommentName,"ACS_Comment_Form ACS_Comment_FormError");
    return false;
  }
  
  /* check if message is ok */
  if(!ACS_newCommentMessage || ACS_newCommentMessage.value.length<ACS_newCommentMessageMinLength.value || ACS_newCommentMessage.value.length>ACS_newCommentMessageMaxLength.value){
    ACS_newCommentMessage.focus();
    ACS_changeClass(ACS_newCommentMessage,"ACS_Comment_Form ACS_Comment_FormError");
    return false;
  }
  
  
  /* check if anti spam code matches */
  if((ACS_newCommentAntiSpamCodeEnabled && ACS_newCommentAntiSpamCodeEnabled.value=="1") && (!ACS_newCommentAntiSpamCode || !ACS_newCommentAntiSpamCodeVerification || ACS_newCommentAntiSpamCode.value!=ACS_newCommentAntiSpamCodeVerification.value)){
    ACS_newCommentAntiSpamCode.focus();
    ACS_changeClass(ACS_newCommentAntiSpamCode,"ACS_Comment_Form ACS_Comment_FormError");
    return false;
  }
  
  /* check if slider is on the right */
  if((ACS_newCommentSliderEnabled && ACS_newCommentSliderEnabled.value=="1") && (ACS_newCommentSlider.value!=1)){
    var ACS_slider = document.getElementById("ACS_slider");
    
    if(ACS_slider){
      ACS_slider.style.borderColor = "#888";
    }
    
    return false;
  }
  
  /* disable submit button and change text */
  ACS_newCommentSubmit.innerHTML += "ting...";
  ACS_newCommentSubmit.disabled = true;
  
  /* remember name or not */
  if(ACS_newCommentRememberName.src.indexOf("unchecked")==-1){
    ACS_setCookie("ACS_CommentName",ACS_newCommentName.value,365,null,null,null);
  }else{
    ACS_deleteCookie("ACS_CommentName");
  }
  
  return true;
}

/* hide comments with slider */
function ACS_hideComments(){  
  /* hide link "Hide comments" and show link "Show comments */
  ACS_hide(document.getElementById("ACS_Comments_Hide"));
  ACS_show(document.getElementById("ACS_Comments_Show"));
  
  /* slide out */
  new Fx.Slide("ACS_Comments").slideOut();
  
  /* set cookie to hide comments */
  ACS_setCookie("ACS_HideComments","yes",365,null,null,null);
  
  /* hide pictures inside comments-container because ie-bug */
  var images = document.getElementById("ACS_Comments").getElementsByTagName("img");
  
  for(var i=images.length-1;i>-1;i--){
    ACS_hide(images[i]);
  }
}

/* show comments with slider */
function ACS_showComments(){
  /* show link "Hide comments" and show link "Show comments */
  ACS_hide(document.getElementById("ACS_Comments_Show"));
  ACS_show(document.getElementById("ACS_Comments_Hide"));
  
  /* show pictures inside comments-container because ie-bug */
  var images = document.getElementById("ACS_Comments").getElementsByTagName("img");
  
  for(var i=images.length-1;i>-1;i--){
    ACS_show(images[i]);
  }
  
  /* slide in */
  ACS_show(document.getElementById("ACS_Comments"));
  new Fx.Slide("ACS_Comments").slideIn();
  
  /* delete cookie to hide comments */
  ACS_deleteCookie("ACS_HideComments");
}

/* text counter */
function ACS_textCounter(field,counter,maxlimit,linecounter) {
  var fieldWidth = parseInt(field.offsetWidth);
  var charcnt = field.value.length; 
  
  /* trim the extra text */
  if(charcnt > maxlimit){
    field.value = field.value.substring(0,maxlimit);
  }else{
    /* progress bar percentage */
    var percentage = parseInt(100-((maxlimit-charcnt)*100)/maxlimit);
    var counter = document.getElementById(counter);
    counter.style.width = parseInt((fieldWidth*percentage)/100)+"px";
    counter.innerHTML = percentage+"%";

    if(percentage==100){
      counter.style.backgroundColor = "rgb(229,135,18)";
      counter.style.display = "block";
    }else{
      counter.style.backgroundColor = "rgb(121,148,112)";

      if(percentage==0){
        counter.style.display = "none";
      }else{
        counter.style.display = "block";
      }
    }
  }
}

/* remember name (change image) */
function ACS_toggleRememberName(img){
  var ACS_path = document.getElementById("ACS_path");

  if(img.src.indexOf("unchecked")!=-1){
    img.src = ACS_path.value+"img/checked.gif";
  }else{
    img.src = ACS_path.value+"img/unchecked.gif";
  }
}

/* set class for element */
function ACS_changeClass(object,className){
  if(object && className){
    object.className = className;
  }
}

/* hide element */
function ACS_hide(object){
  if(object){
    object.style.display = "none";
  }
}

/* show element */
function ACS_show(object){
  if(object){
    object.style.display = "";
  }
}

/* set cooke */
function ACS_getCookie(name){
  var start = document.cookie.indexOf(name+"=");
  var len = start+name.length+1;

  if((!start) && (name!=document.cookie.substring(0,name.length))){
    return null;
  }
  
  if(start==-1){
    return null;
  }
  
  var end = document.cookie.indexOf(";",len);
  
  if(end==-1){
    end = document.cookie.length;
  }
  
  return unescape(document.cookie.substring(len,end));
}

/* set cooke */
function ACS_setCookie(name,value,expires,path,domain,secure){
  var today = new Date();
  
  today.setTime(today.getTime());
  
  if(expires){
    expires = expires*1000*60*60*24;
  }
  
  var expires_date = new Date(today.getTime()+(expires));
  document.cookie = name+"="+escape(value)+((expires) ? ";expires="+expires_date.toGMTString() : "")+((path) ? ";path="+path : "")+((domain) ? ";domain="+domain : "")+((secure) ? ";secure" : "");
}

/* delete cookie */
function ACS_deleteCookie(name,path,domain){
  if(ACS_getCookie(name)){
    document.cookie = name+"="+((path) ? ";path="+path : "")+((domain) ? ";domain="+domain : "")+";expires=Thu, 01-Jan-1970 00:00:01 GMT";
  }
}

function tabMargin(tabDepth){
  link = document.getElementById("ACS_Comments_Container");
  var pixels = tabDepth * 20;
  link.style.cssText = "margin-left:" + pixels.toString() + "px;";
}
