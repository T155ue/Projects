import StageData from "../dataJson/stageData.json" with {type:"json"}
import GameManager from "./GameManager.js";
import "https://code.jquery.com/jquery-1.10.2.min.js";

// make a class for game manager
export default class LearnGameManager extends GameManager {
  constructor(gameStage,userid) {
    super();
    this.userid = userid;
    this.gameState = false;
    this.gameStage = gameStage;
    this.verifyButton = document.getElementById("verify-button");
    this.questionText = document.getElementById("question-text");
    this.stageText = document.getElementById("stage-text");
    this.stageData = StageData.stages[this.gameStage-1]
    this.stageHtml = this.stageData.original_html
    this.stageCss = this.stageData.original_css
    this.stageAnswerCss = this.stageData.answer_css.replace(/\s/g, '');
    this.verifyAnswer = this.verifyAnswer.bind(this);
    this.nextStage = this.nextStage.bind(this);
    

  }
  
  generateStageCssWithInput(stageCss, input) {
    return `
    ${stageCss}
    ${input}
    `
  }

  // start game
  startGame() {
    this.gameState = true;
    this.stageText.innerText = `Stage ${this.gameStage}`;
    this.htmlInput.value = this.stageHtml;
    this.htmlInput.readOnly = true;
    this.questionText.innerText = this.stageData.question;
    this.styleInput.addEventListener("input", ()=>{
      super.iframeUpdateStyle(super.generateHtml(this.generateStageCssWithInput(this.stageCss,this.styleInput.value), this.htmlInput.value));
    });
    this.verifyButton.addEventListener("click",()=>{if(this.verifyAnswer()){
      this.nextStage();
    
    }} );
    console.log("Game Starteds");
    super.iframeUpdateStyle(super.generateHtml(this.stageCss, this.stageHtml));
  }

  
  // end game
  endGame() {
    this.gameState = false;
  }

  verifyAnswer() {
    // check if styleinput.value striped is same with stageAnswerCss
    let striped = this.styleInput.value.replace(/\s/g, '');
    let answer = this.stageAnswerCss
    console.log(striped, answer)
    if (striped === answer) {
      if(this.userid != null){
        $.ajax({
          type: "POST",
          url: "/sdp-assignment/gamemode/learn/nextStage.php",
          data: {userid: this.userid},
          success: function(data){
            console.log("success")
            console.log(data)
          }
          
        });
        }
      if(window.confirm("Correct Answer! Next Stage!")){
        window.location.reload();
      }else{
        window.location.reload();
      }
      this.styleInput.value = "";
      return true
      
      
    } else {
      alert("Wrong Answer");
      return false
    }
    
  }

  nextStage() {
    // Check if there's a next stage available
    if ((this.gameStage-1) < 0){
      this.gameStage = 1;
    }
    if ((this.gameStage-1) < StageData.stages.length) {
      this.gameStage++;
      this.stageText.innerText = `Stage ${this.gameStage}`;
      this.stageData = StageData.stages[(this.gameStage-1)];
      this.stageHtml = this.stageData.original_html;
      this.stageCss = this.stageData.original_css;
      this.stageAnswerCss = this.stageData.answer_css.replace(/\s/g, '');
      this.questionText.innerText = this.stageData.question;
      super.iframeUpdateStyle(super.generateHtml(this.stageCss, this.stageHtml));
    } else {
      alert("Congratulations! You've completed all stages.");
    }
  }

}
