var delId = [];
   function delIdadd(id,press){
    if(press.value == 'off'){
      press.value = 'on';
      let i = delId.indexOf(id);
      if(i !== -1){
        delId.splice(i,1);
      }
    }else{
      press.value = 'off';
      delId.push(id);
    }
   }

   function actall(){
    let i = document.querySelectorAll('input[type="checkbox"]');
    for(let q = 1;q<i.length;q++){
      i[q].click();
    }
   }