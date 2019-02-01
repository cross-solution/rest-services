## Language Guesser

Web service to guess the language of a text passage.
Using this [language detection library](https://github.com/patrickschur/language-detection).

### Usage
Make a POST request to /guess-lang. 

Provide the following parameters:

* languages: List of languages to check against. (optional)
* text: The text to check (required)


You may send the parameters as JSON. You must set the Content-Type header to 'application/json' then.


    {
        "languages": ["de", "en"],
        "text": "I want to know, what language this is in."
    }


The API returns a JSON result like


    {
        "language": "en",
        "result": {
            "en": 0.4596092685143117,
            "de": 0.328759654702408
        },
        "input": {
            "languages": [
                "de",
               "en"
            ],
            "text": "I want to know what language this is."
       }
    }


### Errors

Errors are represented by a JSON object.


    {
        "status": "failure",
        "reason": "invalid input",
        "details": {
            "languages": {
                "notInArray": "Unsupported language: ens"
            }
        }
    }
   
