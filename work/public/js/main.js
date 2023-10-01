'use strict';

{
    //すべてのチェックボックスを取得
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        /*
        チェックボックスが変わった時に、フォームの値を送信する
        fetch()を使って、ページを遷移させずにデータを送信する。
        */
        checkbox.addEventListener('change',() =>{
            const url = '?action=toggle';
            const options = {
                method: 'POST',
                body: new URLSearchParams({
                    id:,
                    token:,
                }),
            }
            fetch(url, options);
        });
    });
    
        //すべてのdeleteを取得
    const deletes = document.querySelectorAll('.delete');
    deletes.forEach(span => {
        span.addEventListener('click',() =>{
            if(!confirm('本当に消しますか？')){
                return;
            }
            span.parentNode.submit();
            //✕印がクリックしたら、フォームの値を送信する。
        });
    });
    
    //チェック済みの全消し
    const purge = document.querySelector('.purge');
    purge.addEventListener('click', () => {
        
        if(!confirm('本当に消しますか？')){
            return;
        }
        purge.parentNode.submit();
        //✕印がクリックしたら、フォームの値を送信する。
    });
}