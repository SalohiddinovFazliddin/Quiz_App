let startBtn = document.getElementById('start-btn');
startBtn.addEventListener('click',()=>{

    async function getQuizItems()
    {
        const {default: apiFetch} = await import('/js/utils/apiFetch.js');
        await apiFetch('/login', {method: 'GET', body: formDate})

            .then((data) => {
                localStorage.setItem('token', data.token);
                window.location.href = '/dashboard';
            })
            .catch((error) =>
            {
                document.getElementById('error').innerHTML = '';
                Object.keys(error.data.errors).forEach(err =>
                {
                    document.getElementById('error').innerHTML += `<p class="text-red-500 mt-1">${error.data.errors[err]}</p>`;
                })
            });
    }

    let startQuizContainer = document.getElementById('start-card');
    startQuizContainer.classList.add('hidden');
    document.getElementById('questionContainer').classList.remove('hidden');
});

function startTimer(duration, display) {
    let timer = duration;
    setInterval(() => {
        const minutes = Math.floor(timer / 60);
        const seconds = timer % 60;
        display.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        if (--timer < 0) {
            timer = 0;
            // Handle timer completion
        }
    }, 1000);
}

// Initialize quiz
let options = document.getElementById('options'),
    questions = [
        {
            'id':1,
            'question': 'What is the output of console.log(typeof undefined)?',
            'options': [
                {
                    'id':1,
                    'option_text':'undefined'
                },
                {
                    'id':2,
                    'option_text':'object'
                },
                {
                    'id':3,
                    'option_text':'string'
                },
                {
                    'id':4,
                    'option_text':'null'
                }
            ],
        },
        {
            'id':2,
            'question': 'What is the output of console.log(typeof null)?',
            'options': [
                {
                    'id':1,
                    'option_text':'undefined'
                },
                {
                    'id':2,
                    'option_text':'object'
                },
                {
                    'id':3,
                    'option_text':'string'
                },
                {
                    'id':4,
                    'option_text':'null'
                }
            ],
        },
        {
            'id':3,
            'question': 'What is the output of console.log(typeof {})?',
            'options': [
                {
                    'id':1,
                    'option_text':'undefined'
                },
                {
                    'id':2,
                    'option_text':'object'
                },
                {
                    'id':3,
                    'option_text':'string'
                },
                {
                    'id':4,
                    'option_text':'null'
                }
            ],
        }
    ],
    currentQuestionIndex = 0;

function takeQuiz(index=0) {
    return questions[index];
}
document.addEventListener('DOMContentLoaded', () => {
    const timerDisplay = document.getElementById('timer');
    startTimer(1200, timerDisplay); // 20 minutes

    // Add event listeners for navigation buttons
    document.getElementById('next-btn').addEventListener('click', () => {
        currentQuestionIndex++;
        let question = takeQuiz(currentQuestionIndex);
        if (question) {
            let questionElement = document.getElementById('question');
            questionElement.textContent = question.question;
            options.innerHTML = '';
            question.options.forEach((option) => {
                options.innerHTML += `
                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="answer" class="h-4 w-4 text-blue-600" value="${option.id}">
                    <span class="ml-3">${option.option_text}</span>
                </label>`
            });
        } else {
            alert('Quiz completed');
        }
    });

    document.getElementById('prev-btn').addEventListener('click', () => {
        currentQuestionIndex--;
        let question = takeQuiz(currentQuestionIndex);
        if (question) {
            let questionElement = document.getElementById('question');
            questionElement.textContent = question.question;
            options.innerHTML = '';
            question.options.forEach((option) => {
                options.innerHTML += `
                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="answer" class="h-4 w-4 text-blue-600" value="${option.id}">
                    <span class="ml-3">${option.option_text}</span>
                </label>`
            });
        } else {
            alert('You are at the first question');
        }
    });

    document.getElementById('submit-quiz').addEventListener('click', () => {
        if (currentQuestionIndex >= 2) {
            currentQuestionIndex--;
        }
        console.log(currentQuestionIndex);
        console.log(questions[currentQuestionIndex]);
        questions.splice(currentQuestionIndex, 1);
        let question = takeQuiz(currentQuestionIndex),
            questionElement = document.getElementById('question'),
            questionContainer = document.getElementById('questionContainer');
        if (question) {
            questionElement.textContent = question.question;
            options.innerHTML = '';
            question.options.forEach((option) => {
                options.innerHTML += `
                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="answer" class="h-4 w-4 text-blue-600" value="${option.id}">
                    <span class="ml-3">${option.option_text}</span>
                </label>`
            });
        } else {
            questionContainer.innerHTML = '';
            document.getElementById('results-card').classList.remove('hidden');
        }
    });
});