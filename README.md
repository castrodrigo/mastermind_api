# mastermind_api
Mastermind API Project

Project Team:
@castrodrigo
@goulartfs
@ecarvalho
@rtancman


# local setup
```
1. clone project
2. run "compose install"
3. run "install mongodb"
4. run "php -s"
```

# Run with docker
```
1. clone project
2. run "docker-compose up -d"
3. access http://localhost
```


##API Documentation

http://docs.vanhackmastermind.apiary.io/

# Mastermind    

A simple API allowing consumers plays a Mastermind game.

## Game [/games]

### Create a New Game [POST]

Creates a new Game

+ Request (application/json)

        { 
            "user": "Player Name" 
        }

+ Response 201 (application/json)

    + Body
    
            {
              "colors": [
                "M",
                "O",
                "B",
                "O",
                "O",
                "M",
                "C",
                "M"
              ],
              "game_key": "5741f5487039f69a348b4584",
              "past_results": [],
              "solved": false,
              "code_length": 8,
              "num_guesses": 0
            }

## Guess [/games/{game_key}/guesses]

+ Parameters
    + game_key (string) - ID of the Game created

### Send a Guess [POST]

This endpoint requires you to POST with the game_key and a 
code consisting of 8 letters of 
RBGYOPCM (corresponding to Red, Blue, Green, Yellow, Orange, Purple, Cyan, Magenta).

+ Request (application/json)

        { 
            "code": "CYYPMRBR"
        }

+ Response 201 (application/json)

    + Body

            {
                "colors": [
                    "M",
                    "O",
                    "B",
                    "O",
                    "O",
                    "M",
                    "C",
                    "M"
                ],
                "game_key": "5741f5487039f69a348b4584",
                "past_results": [
                    {
                      "guess": "CYYPMRBR",
                      "exact": 0,
                      "near": 3
                    }
                ],
                "solved": false,
                "code_length": 8,
                "result": {
                    "exact": 0,
                    "near": 3
                },
                "guess": "CYYPMRBR",
                "num_guesses": 1
            }

    
+ Request (application/json)
    #Winning a Game
    Once you guess the correct code, you will receive the time it 
    took for you to complete the challenge as well as further 
    instructions if you wish to get in touch with us!
        
    + Body

            { 
                "code": "MOBOOMCM"
            }

+ Response 200 (application/json)

    + Body
    
            {
              "colors": [
                "M",
                "O",
                "B",
                "O",
                "O",
                "M",
                "C",
                "M"
              ],
              "game_key": "5741f5487039f69a348b4584",
              "past_results": [
                {
                  "guess": "CYYPMRBR",
                  "exact": 0,
                  "near": 3
                },
                {
                  "guess": "MOBOOMCM",
                  "exact": 8,
                  "near": 0
                }
              ],
              "solved": true,
              "code_length": 8,
              "result": "You win!",
              "further_instructions": "Solve the challenge to see this!",
              "guess": "MOBOOMCM",
              "user": "Player Name",
              "num_guesses": 2,
              "time_taken": 289.55774617195
            }
