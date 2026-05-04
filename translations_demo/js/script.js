// script.js — handles language change, fetch to get_question.php, DOM update, localStorage, simple animation
(function(){
    const buttons = document.querySelectorAll('.lang-btn');
    const questionText = document.getElementById('question-text');
    const answersList = document.getElementById('answers-list');
    const card = document.getElementById('question-card');

    function setDir(dir){
        document.documentElement.dir = dir;
        document.documentElement.lang = localStorage.getItem('lang') || 'fr';
    }

    function animateOut(el){
        el.classList.add('fade-hidden');
        return new Promise(res => setTimeout(res, 220));
    }
    function animateIn(el){
        el.classList.remove('fade-hidden');
    }

    async function fetchAndRender(lang){
        try{
            await animateOut(card);
            const res = await fetch(GET_QUESTION_ENDPOINT + '?lang=' + encodeURIComponent(lang));
            if(!res.ok) throw new Error('Network error');
            const data = await res.json();
            questionText.textContent = data.question;
            answersList.innerHTML = '';
            data.reponses.forEach(r => {
                const li = document.createElement('li');
                li.className = 'answer-item';
                li.textContent = r;
                answersList.appendChild(li);
            });
            setDir(data.dir);
            localStorage.setItem('lang', data.lang);
            setTimeout(()=>animateIn(card), 10);
        }catch(e){
            console.error('fetch error', e);
            setTimeout(()=>animateIn(card), 10);
        }
    }

    buttons.forEach(b=>{
        b.addEventListener('click', ()=>{
            const lang = b.getAttribute('data-lang');
            fetchAndRender(lang);
        });
    });

    // on load, apply saved language
    document.addEventListener('DOMContentLoaded', ()=>{
        const saved = localStorage.getItem('lang') || (new URLSearchParams(location.search)).get('lang') || null;
        if(saved){
            fetchAndRender(saved);
        }
    });
})();
