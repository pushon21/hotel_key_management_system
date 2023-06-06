'use strict'

var api_url = "assets/php/api.php";
var username = '';
var wantedto = '';




// key management system
function set_user()
{
    var username = $("#username").val();
    if(username.length > 2)
    {
        $.cookie('username',username,{ expires : 10 });
        location.href = "g&t.html";
    }else{
        alert('Insert User name');
    }


    // alert($.cookie('username'));
}
function get_user()
{
    return $.cookie('username');
}
function set_wantedto(doings)
{
    $.cookie('wantedto',doings,{ expires : 10 });

    // alert($.cookie('username'));
}
function get_wantedto()
{
    return $.cookie('wantedto');
}

// api work

function api_get_floorroomstatus()
{
    let doing = get_wantedto();
    let dep_arr = [];
   // var dropdowns = document.getElementsByClassName("dropdown-content");
    var dropdowns = document.querySelectorAll(".dropdown-content a");
    for (let i = 0; i < dropdowns.length; i++) {
      //  dropdowns[i].style.backgroundColor = "red";
        var roomid = dropdowns[i].getAttribute('roomid');
        var floorid = dropdowns[i].getAttribute('floorid');
        dep_arr.push({floorid:floorid,roomid:roomid});
        // let xyzzz = document.querySelector(".dropdown-content a[roomid='"+roomid+"'][floorid='"+floorid+"']");
        // xyzzz.style.backgroundColor = 'green';

    }


    $.post(api_url, {action: 'floorroomstatus',localdata: dep_arr,doing:doing}, function(result){
       // var data = result.data['']
        let username = get_user();
        for(i = 0;i < result.data.length;i++)
        {

          //  alert(result.data[i].name);
            let floorx = result.data[i].floor;
            let roomx = result.data[i].room;
            let dddo = result.data[i].giveortake;
            let xyzzz = document.querySelector(".dropdown-content a[roomid='"+roomx+"'][floorid='"+floorx+"']");
            let elm = document.createElement('span');
            elm.classList.add('badge');
          /*
            if(dddo === doing)
            {
               // alert(roomx + floorx);

                elm.innerText = "already done";
                 xyzzz.style.backgroundColor = 'red';
                 xyzzz.append(elm);
                $(xyzzz).addClass(doing);
            }
*/
            // take
            if(doing === 'take')
            {
                if(dddo === 'give')
                {
                   // xyzzz.style.backgroundColor = 'pink';
                    elm.textContent = "available";
                }
            }

            if(doing === 'give')
            {
                let uunnnmmme = result.data[i].name;
                if(uunnnmmme === username)
                {
                    if(dddo === 'take')
                    {
                        xyzzz.style.backgroundColor = 'green';
                        elm.textContent = "Return";
                        xyzzz.append(elm);
                    }

                }else{
                  //  xyzzz.style.backgroundColor = 'purple';
                    elm.textContent = "Taken by other user";
                    elm.setAttribute('title',uunnnmmme);
                   // $(xyzzz).addClass(doing);
                    xyzzz.append(elm);
                  //  xyzzz.style.display = 'none';
                }
            }else if(doing === 'take')
            {
                let uunnnmmme = result.data[i].name;
                if(uunnnmmme === username)
                {
                    xyzzz.style.backgroundColor = 'pink';
                    elm.textContent = "Taken by you";
                    elm.setAttribute('title',uunnnmmme);
                    xyzzz.append(elm);
                }else{
                    xyzzz.style.backgroundColor = 'purple';
                    elm.textContent = "Taken by other user";
                    elm.setAttribute('title',uunnnmmme);
                    xyzzz.style.display = 'none';
                    xyzzz.append(elm);
                }

            }



        }
        // add inside
        dropdowns = document.querySelectorAll(".dropdown-content a");
        if(doing === 'give') // own have key
        {
            for (let i = 0; i < dropdowns.length; i++) {
                var nneelms = dropdowns[i].getElementsByTagName('span');
                // alert(nneelms.length);
                if(nneelms.length)
                {
                   // alert(nneelms.length);
                  //  dropdowns[i].style.backgroundColor = 'pink';
                }else{
                    // dropdowns[i].style.backgroundColor = 'red';
                     dropdowns[i].style.display = "none";
                }
            }
        }

    });
    // take av key



}






function update_roomstatus()
{

}

// dep page
function dep_floor_works()
{

    var dep_arr = [];

    var dropdowns = document.getElementsByClassName("dropdown-content");
    for (let i = 0; i < dropdowns.length; i++) {
       // dropdowns[i].style.backgroundColor = "red";
        var roomid = dropdowns[i].getAttribute('roomid');
        var floorid = dropdowns[i].getAttribute('floorid');
        dep_arr.push({floorid:floorid,roomid:roomid});

    }

    var usernnmme = get_user();
    var actionnnmme = get_wantedto();
    $('#action_status').html("U:"+usernnmme+" A:"+actionnnmme);

}
// gnt page
function gnt_works(doing)
{
    set_wantedto(doing);
}

function  dep_api_udate_action(floor,room,name)
{
    let wanto = get_wantedto();
    $.post(api_url, {action: 'update_wantedto',wantedto : wanto,floor:floor,room:room,name:name}, function(result){
        alert(result.msg);
    });
}

function printDiv(divID) {
    //Get the HTML of div
    var divElements = document.getElementById(divID).innerHTML;
    //Get the HTML of whole page
    var oldPage = document.body.innerHTML;
    //Reset the page's HTML with div's HTML only
    document.body.innerHTML =
        "<html><head><title></title></head><body>" +
        divElements + "</body>";
    //Print Page
    window.print();
    //Restore orignal HTML
    document.body.innerHTML = oldPage;

}



