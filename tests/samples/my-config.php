<?php

/*
This is encapsulated config 
----------------------------
As the below $configuration contains classes from javascript such as Header,Delimiter etc...
and have not doublequoted keys in PHP, json_decode($configuration,true) 
will fail on it unless you fix $configuration using json_fix($configuration)
I discussed it deeply on Test-JSONFIX.php in https://github.com/sos-productions/Services_JSON

So will have the same configuration file on both JS and PHP side thanks to the JS/PHP bridge
for JSON I made patching Services_JSON to add the class support. 
I guess this will solve https://github.com/codex-team/editor.js/issues/1045
 
*/

$configuration = <<< JSCONFIG
    {
	tools : {
            header: {
                class: Header,
                inlineToolbar: ['link','marker'],
                config: {
                    placeholder: 'Header',
                    levels: [1, 2, 3, 4],
                    defaultLevel: 2
                },
                shortcut: 'CMD+SHIFT+H',
                text: {
                    type: "string",
                    allowedTags: ""
                }
            },
            delimiter: Delimiter,
            underline: Underline,
            paragraph: {
                inlineToolbar: true,
                text: {
                    type: "string",
                    allowedTags: "i,b,u,a[href],strong,em,br"
                }
            },
            image: SimpleImage,
            inlineCode: {
                class: InlineCode,
                shortcut: 'CMD+SHIFT+C'
            },
            linkTool: LinkTool,
            quote: {
                class: Quote,
                inlineToolbar: true,
                shortcut: 'CMD+SHIFT+O',
                config: {
                    quotePlaceholder: 'Enter a quote',
                    captionPlaceholder: 'Quote\'s author',
                }
            } 
        }
    }
JSCONFIG;

?>
