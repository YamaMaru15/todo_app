'use strict';

{
    //各要素ではなく簡略化してmainで送ったトークンを受け取る。
    const token = document.querySelector('main').dataset.token;
    //todoの受け取り
    const input = document.querySelector('[name="title"]');
    //ulの取得
    const ul = document.querySelector('ul');

    // ページ読み込み時のフォーカス
    input.focus();

    //イベントの伝播を利用し、非同期で新しく追加された要素にもイベントを設定
    ul.addEventListener('click', e => {
        if (e.target.type === 'checkbox') {
            //fetch()を使って、ページを遷移させずにデータを送信する。
            // 第一引数、formで送信するaction属性。第二引数送信内容
            // クエリ文字列でpostフォームを区別する
            fetch('?action=toggle', {
                //送信形式
                method: 'POST',
                //送信する内容 idとトークン
                body: new URLSearchParams({ 
                    id: e.target.parentNode.dataset.id,
                    token: token,
                }),
            })
            // データの整合性チェック 別ウィンド等で既にtodoが削除されていた場合アラートを出す
            //todo.php L70の結果を受け取る。
            .then(response => {
                if (!response.ok) {
                    throw new Error('このtodoは既に削除されています!');
                }
                return response.json();
            })
            //todoのチェック状態の整合性を保つ処理
            //todo.php L90とL32(最新DBの値)で送られたis_doneの値が同じかどうかをチェック
            //更新がすでに入っていた場合は、メッセージを表示し、表示を最新DBのis_doneに変更する。
            .then(json => {
                if (json.is_done !== e.target.checked) {
                    alert('このtodoは既に更新されています!');
                    e.target.chcked = json.is_done;
                }
            })
            //上で投げたエラーオブジェクトを受け取ってメッセージを表示、ページを最新の状態にする
            .catch (err => {
                alert(err.message);
                location.reload();
            });
        }

        if (e.target.classList.contains('delete')) {
            if(!confirm('本当に消しますか？')){
                return;
            }
            // クエリ文字列でpostフォームを区別する
            fetch('?action=delete', {
                //送信形式
                method: 'POST',
                //送信する内容 idとトークン
                body: new URLSearchParams({ 
                    id: e.target.parentNode.dataset.id,
                    token: token,
                }),
            });

            e.target.parentNode.remove();
        }
    
    });

    //非同期 todoの追加処理（idと保持しているtitleを仮引数で受け取る）
    function addTodo(id, titleValue) {
        //li要素の作成
        const li = document.createElement('li');
        li.dataset.id = id;
        //input要素の作成(チェックボックス)
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        //title要素の作成(todo) 
        const title = document.createElement('span');
        title.textContent = titleValue;
        //delete要素の作成(✕)
        const deleteSpan = document.createElement('span');
        deleteSpan.textContent = '✕';
        deleteSpan.classList.add('delete');

        //li要素の追加順番指定。checkbox title ✕ を追加
        li.appendChild(checkbox);
        li.appendChild(title);
        li.appendChild(deleteSpan);

        //liをulの最初に追加
        ul.insertBefore(li, ul.firstChild);

    }

    //非同期での値の取り出し。fetch thenを使用する。
    //フォームを送信した時にページを更新したくないのでpreventDefault()でキャンセル
    document.querySelector('form').addEventListener('submit', e => {
        e.preventDefault();

        //title(todo)の保持。非同期により、titleが追加されなくなる不具合を避ける
        const title = input.value;

        fetch('?action=add', {
            //送信形式
            method: 'POST',
            //送信する内容 titleとトークン
            body: new URLSearchParams({ 
                title: title,
                token: token,
            }),
        })
        // .then(respnse) => {
        //     return respnse.json();
        // })
        //returnのみなので省略
        .then(response => response.json())
        .then(json => {
            // idと、非同期処理のため保持している titleの値を使用したいので実引数で渡す。
            addTodo(json.id,title);
        });
        console.log(input.value);
        console.log(input);


        // todo登録後の空文字挿入と、フォーカス
        input.value = '';
        input.focus();
    });

    // ulでイベントを伝播させて処理するため不要
    // //すべてのチェックボックスを取得
    // const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    // checkboxes.forEach(checkbox => {
    //     /*
    //     チェックボックスが変わった時に、フォームの値を送信する
    //     fetch()を使って、ページを遷移させずにデータを送信する。
    //     */
    //     checkbox.addEventListener('change',() =>{
    //         //第一引数、formで送信するaction属性。第二引数送信内容
    //         // クエリ文字列でpostフォームを区別する
    //         fetch('?action=toggle', {
    //             //送信形式
    //             method: 'POST',
    //             //送信する内容 idとトークン
    //             body: new URLSearchParams({ 
    //                 id: checkbox.parentNode.dataset.id,
    //                 token: token,
    //             }),
    //         });
    //         // checkboxの次の兄弟要素のに対してdoneクラス をつけ外しする。
    //         //→cssで変更するため不要
    //         // checkbox.nextElementSibling.classList.toggle('done');
    //     });
    // });
    

    // ulでイベントを伝播させて処理するため不要
    //     //すべてのdeleteを取得
    // const deletes = document.querySelectorAll('.delete');
    // deletes.forEach(span => {
    //     span.addEventListener('click',() =>{
    //         if(!confirm('本当に消しますか？')){
    //             return;
    //         }
    //         // クエリ文字列でpostフォームを区別する
    //         fetch('?action=delete', {
    //             //送信形式
    //             method: 'POST',
    //             //送信する内容 idとトークン
    //             body: new URLSearchParams({ 
    //                 id: span.parentNode.dataset.id,
    //                 token: token,
    //             }),
    //         });

    //         span.parentNode.remove();
    //         //span.parentNode.submit();
    //         //✕印がクリックしたら、フォームの値を送信する。
    //         //→同期処理で処理するので不要
    //     });
    // });
    
    //チェック済みの全消し
    const purge = document.querySelector('.purge');
    purge.addEventListener('click', () => {
        
        if(!confirm('本当に消しますか？')){
            return;
        }
            // クエリ文字列でpostフォームを区別する
        fetch('?action=purge', {
            //送信形式
            method: 'POST',
            //送信する内容 idとトークン
            body: new URLSearchParams({ 
                token: token,
            }),
        });

        //全てのli要素を取得する
        const lis = document.querySelectorAll('li');
        //todoと✕がliを構成。liがチェックされていたら、削除する
        lis.forEach( li => {
            if(li.children[0].checked){
                li.remove()
            }
        });
        // purge.parentNode.submit();
        //✕印がクリックしたら、フォームの値を送信する。
        //→同期処理で処理するので不要
    });
}