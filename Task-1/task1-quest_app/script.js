const questions = [
  {
    question:
      "Internet sitelerinin guvenligini artirmak icin HTTP yerine kullanilan protokol nedir?",
    answers: [
      { text: "FTP", correct: false },
      { text: "SMTP", correct: false },
      { text: "HTTPS", correct: true },
      { text: "POP3", correct: false },
    ],
  },
  {
    question:
      "Bilgisayarinizdaki zararli yazilimlari tespit edip temizleyen yazilim turu nedir?",
    answers: [
      { text: "Firewal", correct: false },
      { text: "VPN", correct: false },
      { text: "Antivirus", correct: true },
      { text: "Proxy", correct: false },
    ],
  },
  {
    question:
      "Web sitelerinde kullanici girisi sirasinda kullanilan ve insanlari botlardan ayirt eden test nedir?",
    answers: [
      { text: "Brute Force", correct: false },
      { text: "CAPTCHA", correct: true },
      { text: "SQL Enjeksiyonu", correct: false },
      { text: "Firewall", correct: false },
    ],
  },
  {
    question:
      "Bir saldirganin bir kullanicinin oturumunu ele gecirmesi icin kullanilan en yaygin yontemlerden biri nedir?",
    answers: [
      { text: "Ransomware", correct: false },
      { text: "Kimlik avi (Phishing)", correct: true },
      { text: "DDoS", correct: false },
      { text: "Sniffing", correct: false },
    ],
  },
  {
    question:
      "Bir ag veya sistemde, izin verilen islemler disinda faaliyetler gerceklestirilmesine izin veren guvenlik acigi nedir?",
    answers: [
      { text: "XSS", correct: true },
      { text: "VPN", correct: false },
      { text: "Antivirus", correct: false },
      { text: "Proxy", correct: false },
    ],
  },
  {
    question:
      "Web uygulamalarinda veri tabani sorgularini manipule etmek amaciyla yapilan saldiri turu nedir?",
    answers: [
      { text: "SQL Enjeksiyonu", correct: true },
      { text: "XSS", correct: false },
      { text: "CSRF", correct: false },
      { text: "Spoofing", correct: false },
    ],
  },
];

const questionElement = document.getElementById("question");
const answerButtons = document.getElementById("answer-buttons");
const nextButton = document.getElementById("next-btn");

const adminPanelBtn = document.getElementById("admin-panel");
const adminPanelSection = document.getElementById("admin-panel-section");
const questionListAdmin = document.getElementById("question-list-admin");
const searchBox = document.getElementById("search-box");

let currentQuestionIndex = 0;
let score = 0;

function startQuiz() {
  currentQuestionIndex = 0;
  score = 0;
  nextButton.innerHTML = "Next";
  showQuestion();
}

function showQuestion() {
  resetState();
  let currentQuestion = questions[currentQuestionIndex];
  let questionNo = currentQuestionIndex + 1;
  questionElement.innerHTML = questionNo + ". " + currentQuestion.question;

  currentQuestion.answers.forEach((answer) => {
    const button = document.createElement("button");
    button.innerHTML = answer.text;
    button.classList.add("btn");
    answerButtons.appendChild(button);
    if (answer.correct) {
      button.dataset.correct = answer.correct;
    }
    button.addEventListener("click", selectAnswer);
  });
}

function resetState() {
  nextButton.style.display = "none";
  while (answerButtons.firstChild) {
    answerButtons.removeChild(answerButtons.firstChild);
  }
}

function selectAnswer(e) {
  const selectedBtn = e.target;
  const isCorrect = selectedBtn.dataset.correct === "true";
  if (isCorrect) {
    selectedBtn.classList.add("correct");
    score++;
  } else {
    selectedBtn.classList.add("incorrect");
  }
  Array.from(answerButtons.children).forEach((button) => {
    if (button.dataset.correct === "true") {
      button.classList.add("correct");
    }
    button.disabled = true;
  });

  nextButton.style.display = "block";
}

adminPanelBtn.addEventListener("click", () => {
  document.querySelector("h1").style.display = "none";
  adminPanelBtn.style.display = "none";
  document.getElementById("question-list").style.display = "none";
  adminPanelSection.classList.remove("hide");
  showAdminPanel();
});
function showAdminPanel() {
  questionListAdmin.innerHTML = ""; // Listeyi temizle
  questions.forEach((q, index) => {
    const listItem = document.createElement("div");
    listItem.classList.add("question-container");
    listItem.innerHTML = `
        <div class="question-text">
          ${index + 1}. ${q.question}
        </div>
        <div class="button-container">
          <button class="btn" onclick="editQuestion(${index})">Düzenle</button>
          <button class="btn" onclick="deleteQuestion(${index})">Sil</button>
        </div>
      `;
    questionListAdmin.appendChild(listItem);
  });
}

function editQuestion(index) {
  const newQuestion = prompt("Yeni soruyu girin:", questions[index].question);
  if (newQuestion) {
    questions[index].question = newQuestion;
    showAdminPanel();
  }
}
function deleteQuestion(index) {
  questions.splice(index, 1);
  showAdminPanel();
}

searchBox.addEventListener("input", () => {
  const query = searchBox.value.toLowerCase();
  Array.from(questionListAdmin.children).forEach((item) => {
    const text = item.textContent.toLowerCase();
    item.style.display = text.includes(query) ? "list-item" : "none";
  });
});

function showScore() {
  resetState();
  questionElement.innerHTML = `You scored ${score} out of ${questions.length}!`;
  next.Button.innerHTML = "Play Again";
  nextButton.style.display = "block";
}

function handleNextButton() {
  currentQuestionIndex++;
  if (currentQuestionIndex < questions.length) {
    showQuestion();
  } else {
    showScore();
  }
}
document.getElementById("question-list").addEventListener("click", () => {
  document.querySelector("h1").style.display = "none";
  document.querySelector(".btn:first-of-type").style.display = "none";
  document.getElementById("question-list").style.display = "none";

  document.querySelector(".quiz").classList.remove("hide");

  startQuiz();
});
nextButton.addEventListener("click", () => {
  if (currentQuestionIndex < questions.length) {
    handleNextButton();
  } else {
    startQuiz();
  }
});

startQuiz();
