# Project 2, Milestone 1 - Design & Plan

Your Name:

## 1. Persona

I've selected Abby my persona.

I've selected my persona because I think designing for Abby will also make the site
accessible the most amount of people as possible, since I will be forced to think
about designing a user friendly and intuitive site

## 2.Describe your Catalog

[What will your collection be about? What types of attributes (database columns) will you keep track of for the *things* in your collection? 1-2 sentences.]

My collection will be about video games. I will keep track of their name, release year, genre, and critic score.

## 3. Sketch & Wireframe


![sketch](/sketch.jpg)

![wireframe](/wireframe.jpg)

![wireframe2](/wireframe2.jpg)

![wireframe3](/wireframe3.jpg)


I think this would be an effective design for Abby since it is clear and easy to read. Furthermore the search features make it simple to find what the user is looking for.

## 4. Database Schema Design

[Describe the structure of your database. You may use words or a picture. A bulleted list is probably the simplest way to do this.]

Table: games
* field 1: title - the name of the video game (TEXT) NOT NULL
* field 2: release - the year of release of the game (INTEGER) NOT NULL
* field 3: genre - the genre of the video game (TEXT) NOT NULL
* field 4: score - The "metacritic" rating of the game (INTEGER) NOT NULL

## 5. Database Query Plan

[Plan your database queries. You may use natural language, pseudocode, or SQL.]

1. All records

  SELECT * FROM games;

2. Search records by user selected field

  SELECT * FROM user_selection

3. Insert record

  INSERT INTO games(title,release,genre,score)
    VALUES("overwatch",2016,"shooter",91)

## 6. *Filter Input, Escape Output* Plan

[Describe your plan for filtering the input from your HTML forms. Describe your plan for escaping any values that you use in HTML or SQL. You may use natural language and/or pseudocode.]

I will set the parameter marker => to the $variable marker and then use a $query
to prepare the variable and execute the parameter. Which should filter the code.

## 7. Additional Code Planning

[If you need more code planning that didn't fit into the above sections, put it here.]
