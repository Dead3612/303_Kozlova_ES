### Описание структуры файлов данных

##### genres.txt
    Поля:
        genres: string;

    Структура: линейная
    Количество строк: 18

##### movies.csv
    Поля:
        movieId: integer;
        title:   string;
        genres:  string;

    Структура: табличная
    Разделитель: ','
    Количество строк: 9742

##### occupation.txt
    Поля:
        occupation: string;

    Структура: линейная
    Количество строк: 21

##### ratings.csv
     Поля:
         userId:    integer;
         movieId:   integer;
         rating:    float;
         timestamp: integer;  

    Структура: табличная
    Разделитель: ','
    Количество строк: 22417

##### ratings_count.txt
    Поля:
        id:     integer;
        counts: integer;

    Структура: табличная
    Количество строк: 2

##### tags.csv
     Поля:
         userId:    integer;
         movieId:   integer;
         tag:       string;
         timestamp: integer;  

    Структура: табличная
    Разделитель: ','
    Количество строк: 3683

##### users.txt
    Поля:
        id:         integer;
        name:       string;
        email:      string;
        sex:        string;
        date:       datetime;
        profession: string;

    Структура: табличная
    Разделитель: '|'
    Количество строк: 943
