//Author of JS: Hayden Arceneaux

const clientReason = document.getElementById ("clientReason") ;
const newOption = document.getElementById ("otherReason" ) ;
const newOptionLabel = document.querySelector ('label[for="otherReason"]')

//event listener to add a "specify" text field when the option "other" is selected
clientReason.addEventListener ("change",
    function()
    {
        if (this.value == "other")
        {
            newOption.style.display = "block" ;
            newOption.required = true ;
            newOptionLabel.style.display = "block" ;
        }
        else
        {
            newOption.style.display = "none" ;
            newOption.required = false ;
            newOptionLabel.style.display = "none" ;
        }
    } 
) ;

//I want to add another event listener or onclick function to call/somehow activate the clientReason listener for when the form is cleared
