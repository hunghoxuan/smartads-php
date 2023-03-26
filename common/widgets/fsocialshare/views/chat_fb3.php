<div id="fb_comment"></div>

<script>
    window.appId="1687092974925132",
        window.pageId="1681597472146812",
        window.baseURL="https://"+(-1!==window.location.host.indexOf("gostream")?window.location.host:"gostream.co"),
        window.fbAsyncInit=function(){window.FB.init({appId:window.appId,xfbml:!0,autoLogAppEvents:!0,version:"v2.11",cookie:!0,oauth:!0}),
            window.FB.Event.subscribe("messenger_checkbox",function(e){if(console.log("messenger_checkbox event"),console.log(e),"rendered"==e.event)console.log("Plugin was rendered");else if("checkbox"==e.event){var o=e.state;console.log("Checkbox state: "+o)}else"not_you"==e.event?console.log("User clicked 'not you'"):"hidden"==e.event&&console.log("Plugin was hidden")})},function(e,o,n){var t,s=e.getElementsByTagName(o)[0];e.getElementById(n)||((t=e.createElement(o)).id=n,t.src="//connect.facebook.net/en_US/sdk.js",s.parentNode.insertBefore(t,s))}(document,"script","facebook-jssdk")</script><script type="text/javascript">setTimeout(function () {
        var el = document.createElement("div");
        el.setAttribute('class', 'fb-customerchat');
        el.setAttribute('page_id', window.pageId);
        el.setAttribute('ref', '');
        el.setAttribute('minimized', 'true');
        document.getElementById("fb_comment").appendChild(el);
        if ( FB )
            FB.XFBML.parse(document.getElementById("fb_comment"));
    }, 1000);
    setTimeout(function() {
        var ifr = document.getElementsByTagName("iframe");
        for ( var i=0; i<ifr.length; i++ ) {
            if ( ifr[i].getAttribute("title") && ifr[i].getAttribute("title").indexOf("fb:customerchat")===0 ) {
                ifr[i].style.display = "none";
                break;
            }
        }
    }, 4000);
</script>

