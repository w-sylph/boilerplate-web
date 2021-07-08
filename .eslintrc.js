const INLINE_ELEMENTS = ["a", "abbr", "audio", "b", "bdi", "bdo", "canvas", "cite", "code", "data", "del", "dfn", "em", "i", "iframe", "ins", "kbd", "label", "map", "mark", "noscript", "object", "output", "picture", "q", "ruby", "s", "samp", "small", "span", "strong", "sub", "sup", "svg", "time", "u", "var", "video"];

module.exports = {
	/* global variables to prevent no-unused-vars */
	"globals": {
        "axios": "readonly",
        "moment": "readonly",
        "require": "readonly",
        "$": "readonly",
        "Vue": "readonly",
        "process": "readonly",
        "Swiper": "readonly",
        "docReady": "readonly",
        "Viewer": "readonly",
        "lightGallery": "readonly"
    },
	"extends": [
		"eslint:recommended",
		"plugin:vue/essential",
		"plugin:vue/strongly-recommended",
		"plugin:vue/recommended"
	],
	"rules": {
		/* indentions and spacing */
		"vue/html-indent": ["error", 4, {
			"ignores": [ ...INLINE_ELEMENTS ]
		}],
		"vue/script-indent": ["error", 4, {
			"baseIndent": 0,
			"switchCase": 1
		}],
		"vue/singleline-html-element-content-newline": ["error", {
			"ignoreWhenNoAttributes": true,
			"ignoreWhenEmpty": true,
			"ignores": [ ...INLINE_ELEMENTS ]
		}],
		"vue/padding-line-between-blocks": ["error", "always"],

		/* naming convention */
		"vue/name-property-casing": ["error", "kebab-case"],
		"vue/component-definition-name-casing": ["error", "kebab-case"],
		"vue/component-name-in-template-casing": ["error", "kebab-case"],

		/* error checking */
		"vue/no-reserved-component-names": ["error"],
		"vue/no-irregular-whitespace": ["error", {
	        "skipStrings": true,
	        "skipComments": false,
	        "skipRegExps": false,
	        "skipTemplates": false,
	        "skipHTMLAttributeValues": false,
	        "skipHTMLTextContents": false
	    }],
	    "vue/valid-v-slot": ["error"],
	    "vue/require-name-property": ["error"],
	    "vue/no-v-html": ["off"],

	    /* code pattern */
		"vue/component-tags-order": ["error", {
			"order": ["template", "script", "style"]
		}],

		/* js naming convention */
		"camelcase": ["error", {
			"properties": "never",
			"ignoreDestructuring": false,
		}],

	    /* js spaces and code pattern */
		"arrow-spacing": ["error", {
			"before": true,
			"after": true
		}],
		"array-bracket-spacing": ["error", "always"],
		"brace-style": ["error"],
		"key-spacing": ["error", { "beforeColon": false }],
		"keyword-spacing": ["error", {
			"before": true,
			"after": true
		}],
		"comma-dangle": ["error", "never"],
		"semi": ["error", "always"],
		"no-trailing-spaces": ["error"],
		"no-unused-vars": ["warn", {
	    	"args": "after-used",
	    	"vars": "local",
	    }],
	},
}