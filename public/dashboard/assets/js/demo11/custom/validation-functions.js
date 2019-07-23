function validate(data, validation)
{
    if(validation == "required")
    {
        if(requireValidate(data))
        {
            return true;
        }
    }
    else if(validation.includes("min"))
    {
        var res = validation.split(":");
        minValue = res[1];
        if(minValidate(data, minValue))
        {
            return true;
        }
    }
    else if(validation.includes("max"))
    {
        var res = validation.split(":");
        maxValue = res[1];
        if(maxValidate(data, maxValue))
        {
            return true;
        }
    }
    else if(validation == "email")
    {
        if(emailValidate(data))
        {
            return true;
        }
    }
}

function displayError(controlId, field_name, custom_message = null)
{
    $(controlId).addClass("text-danger");
    $(controlId).removeClass("text-muted");
    if(custom_message != null)
    {
        $(controlId).text(custom_message);
    }
    else{
        $(controlId).text("The "+ field_name + " field is required.");
    }
}

function requireValidate(data)
{
    if(data == "" || data == "--" || data == null || data == undefined)
    {
        return true;
    }
}
function minValidate(data, minValue)
{
    if(data.length < minValue)
    {
        return true;
    }
}
function maxValidate(data, minValue)
{
    if(data.length > maxValue)
    {
        return true;
    }
}
function emailValidate(data)
{
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(re.test(String(data).toLowerCase()) == false)
    {
        return true;
    }
}
function matchPasswords(value1, value2)
{
    if(value1 != value2)
    {
        return true;
    }
}

function resetValidationErrors(controlId, message)
{
    $(controlId).removeClass("text-danger");
    $(controlId).addClass("text-muted");
    $(controlId).text(message);
}