function validation(f) {
    if (f.mdp.value == '' || f.confirmemdp.value == ''){
      alert('Tous les champs ne sont pas remplis');
      f.mdp.focus();
      return false;
    }
    else if (f.mdp.value != f.confirmemdp.value){
      alert('Ce ne sont pas les mêmes mots de passe!');
      f.mdp.focus();
      return false;
    }
    else if (f.mdp.value == f.confirmemdp.value){
      return true;
    }
    else {
      f.mdp.focus();
      return false;
    }
}

function modification(f) {
  if (f.mdp.value == '' && f.confirmemdp.value == '' && f.ancienmdp.value == ''){
    return true;
  }
  else if (f.mdp.value == '' || f.confirmemdp.value == '' || f.ancienmdp.value == ''){
    alert('Les champs de mots de passe ne sont pas tous remplis!');
    f.ancienmdp.focus();
    return false;
  }
  else {
    if (f.mdp.value != f.confirmemdp.value){
      alert('Les nouveaux mots de passe ne sont pas identique!');
      f.mdp.focus();
      return false;
    }
    else if (f.mdp.value == f.confirmemdp.value){
      return true;
    }
    else {
      f.mdp.focus();
      return false;
    }
  }
}