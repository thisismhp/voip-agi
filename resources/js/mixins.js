export const mixins = {
    parseValidation : function (res) {
        let errorsString = '';
        const data = res.data;
        if(data){
            const errors = data.errors;
            if(errors) {
                for(let i in errors){
                    const error = errors[i];
                    if(Array.isArray(error)){
                        error.forEach((err) => {
                            errorsString += `${err} <br />`;
                        });
                    }else{
                        errorsString += `${error} <br />`;
                    }
                }
                return errorsString;
            }
            return 'ورودی ها اشتباه است';
        }
        else{
            return 'ورودی ها اشتباه است';
        }
    }
};

