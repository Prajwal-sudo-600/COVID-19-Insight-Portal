const regexRules = {
    fullName: /^[A-Za-z]+([ '-][A-Za-z]+)*$/,
    username: /^[A-Za-z][A-Za-z0-9_]{3,15}$/,
    email: /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/,
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/,
    phone: /^[6-9]\d{9}$/,
    age: /^(?:[1-9]|[1-9]\d|1[01]\d|120)$/,
    city: /^(?=.*[A-Za-z])[A-Za-z ]{2,40}$/,
    htmlTag: /<[^>]*>/
};

function validateField(fieldName, value) {
    if (fieldName === 'fullName') {
        if (value.length < 2 || value.length > 50) return false;
        return regexRules.fullName.test(value);
    }
    if (fieldName === 'username') return regexRules.username.test(value);
    if (fieldName === 'email') return regexRules.email.test(value);
    if (fieldName === 'password') return regexRules.password.test(value);
    if (fieldName === 'phone') return regexRules.phone.test(value);
    if (fieldName === 'age') return regexRules.age.test(value);
    if (fieldName === 'city') return regexRules.city.test(value);
    if (fieldName === 'comment') {
        if (value.length < 1 || value.length > 500) return false;
        return !regexRules.htmlTag.test(value);
    }
    return false;
}
