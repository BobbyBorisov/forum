let auth_user = window.App.user;

let authorizations = {
    updateReply(reply){
        return reply.user_id === auth_user.id;
    },
    updateThread(thread){
        return thread.creator.id === auth_user.id;
    },
    owns(model, prop = 'user_id'){
        return model[prop] === auth_user.id;
    }
};

module.exports = authorizations;