let auth_user = window.App.user;

let authorizations = {
    updateReply(reply){
        return reply.user_id === auth_user.id;
    },
    markAsBestReply(thread){
        return thread.creator.id === auth_user.id;
    }
};

module.exports = authorizations;