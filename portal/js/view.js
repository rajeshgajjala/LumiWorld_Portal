function renderSteps(step){
    var stepsHTML = '<div class="fl message-step' + (step == 1 ? '-active' : '') + '" id="step-recipients" style="z-index: 4">1. Recipients</div>';
    stepsHTML += '<div class="fl message-step' + (step == 2 ? '-active' : '') + '" id="step-message" style="margin-left: -20px; z-index: 3">2. Message</div>'
    stepsHTML += '<div class="fl message-step' + (step == 3 ? '-active' : '') + '" id="step-review" style="margin-left: -20px; z-index: 2">3. Scheduling</div>';
    stepsHTML += '<div class="fl message-step' + (step == 4 ? '-active' : '') + '" id="step-review" style="margin-left: -20px; z-index: 1">4. Review & Send</div>';
    $('.message-steps').html(stepsHTML);
}