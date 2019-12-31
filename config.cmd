rem defaults here
set XAMPP_DIR_DEFAULT=C:/xampp/

rem a list of config defaults
set CONFIGS=XAMPP_DIR

rem now set the configs with their defaults if missing
for %%c in (%CONFIGS%) do (
    if not defined %%c (
        set %%c=!%%c_DEFAULT!
    )
    echo %%c=!%%c!
)
