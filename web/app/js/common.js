/**
 * Created by chrischen on 2017/4/24.
 */
function getEle(_class) {
    return document.querySelector(_class);
}

function Tips(){
    var obj = document.createElement('div');
    var box = document.createElement('div');
    var con = document.createElement('div');
    var txt = document.createElement('div');
    var p = document.createElement('p');
    var btn = document.createElement('span');

    obj.className = 'pop_wrapper none';
    box.className = 'pop_outer';
    con.className = 'pop_cont';
    txt.className = 'pop_tip';
    p.className = 'border b_top';
    btn.className = 'pop_wbtn';
    btn.innerHTML = '我知道了';

    p.appendChild(btn);
    con.appendChild(txt);
    con.appendChild(p);
    box.appendChild(con);
    obj.appendChild(box);
    if(script){
        script.parentNode.insertBefore(obj,script);
    }else{
        document.body.appendChild(obj);
    }

    function hideFun(){
        obj.classList.add('none');
    }

    this.show = function(value,callback){
        var fun = callback || hideFun;
        txt.innerHTML = value || ' ';
        btn.onclick = callback || hideFun;
        obj.classList.remove('none');
    };

    this.hide = hideFun;
}