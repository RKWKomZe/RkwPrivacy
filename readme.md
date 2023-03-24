# rkw_privacy
This extension comes with two main features:

## Copying privacy information
If you are using ext:sg_cookie_optin in a multi-domain-setup you normally have to create a privacy-dataset for each domain, even if this means a lot of copy and paste.
Using this extension you can create one privacy-dataset and copy it automatically in every domain you need.

1) Just define a storagePid of the dataset you want to use via TypoScript
```
plugin.tx_rkwprivacy {

	settings {
        sgCookieOptIn {

            # cat=plugin.tx_rkwprivacy//a; type=int; label=Pid to load data from
			storagePid = 1
		}
    }
}
```
2) Define the pid for imprint and privacy-declaration via TypoScript
```
plugin.tx_rkwprivacy {

	settings {

	    # cat=plugin.tx_rkwprivacy//a; type=int; label=Pid of data protection declaration
        dataProtectionPid = 1921

        # cat=plugin.tx_rkwprivacy//a; type=int; label=Pid of imprint
        imprintPid = 409

        sgCookieOptIn {
            # cat=plugin.tx_rkwprivacy//a; type=int; label=Pid to load data from
			storagePid = 1
		}
    }
}
```
3) Run `vendor/bin/typo3 rkw_privacy:generateStaticFiles` via CLI - ready!

## JavaScript-object for imprint and privacy-declaration
This extension automatically adds a JavaScript-object to your page, which refers to the configured pages for imprint and privacy-declaration.
This can be useful if you need this information in some JavaScripts you use.

Example:
```
<script type="text/javascript">
    var rkwPrivacy = {
        imprint : {
            url : 'http://rkw-bremen.rkw.local/impressum/',
            name : 'Impressum'
        },
        declaration : {
            url : 'http://rkw-bremen.rkw.local/datenschutzerklaerung/',
            name : 'Datenschutzerklärung'
        }
    };
</script>
```
