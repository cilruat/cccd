function checkSubmit(){
    var memberId = $('.id');
    var memberPw = $('.pw');

    if(memberId.val() == ''){
        alert('아이디를 입력해 주세요.');
        return false;
    }
    if(memberPw.val() == ''){
        alert('비밀번호를 입력해 주세요.');
        return false;
    }
   
    console.log(memberId.val());
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'http://192.168.0.4/bbs/bbsLogin.html',
            data: {memberId: memberId.val()},

            success: function (json) {
                if(json.res == 'good') {
                    console.log(json.res);
                    memberIdComment.text('가입하지 않은 아이디거나, 잘못된 비밀번호 입니다.');
                    idCheck.val('1');
                }else{
                    memberIdComment.text('가입하지 않은 아이디거나, 잘못된 비밀번호 입니다.');
                    memberId.focus();
                }
            },
            error: function(){
              console.log('failed');
            }
        })
    return true;
}