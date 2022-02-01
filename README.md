# MedTesterApi

**Uruchomienie projektu**

Pierwsze uruchomienie projektu:
```
make start
```

Należy dodać do pliku /etc/hosts domenę "medtester.dev" oraz "api.medtester.dev".

Uruchomienie kontenera
```
make up
```

Uruchomienie kontenera jako demon
```
make up_daemon
```

W celu uruchomienia wczytywania danych domyślnych należy użyć komendy:
```
make fixtures
```

Dzięki niemu będziemy mieli dostęp do takich kont jak:

Pacjent:
email: kacper@patient.pl
hasło: test1234

Pracownik laboratorium:
email: kacper@lab.pl
hasło: test1234